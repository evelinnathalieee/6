<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Promo;
use App\Models\RewardRedemption;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use App\Support\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        $menuItems = MenuItem::query()
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $now = now();
        $promos = Promo::query()
            ->orderByDesc('starts_at')
            ->get()
            ->filter(fn (Promo $p) => $p->isActive($now))
            ->values();

        return view('admin.sales.create', [
            'menuItems' => $menuItems,
            'promos' => $promos,
            'prefillMemberEmail' => $request->string('member_email')->toString(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_email' => ['nullable', 'email', 'max:190'],
            'order_type' => ['required', 'in:dine_in,take_away'],
            'payment_method' => ['required', 'in:cash,qris'],
            'purchased_at' => ['nullable', 'date'],
            'note' => ['required', 'string', 'max:500'],
            'use_reward' => ['nullable', 'boolean'],
            'promo_id' => ['nullable', 'integer', 'exists:promos,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['nullable', 'integer', 'exists:menu_items,id'],
            'items.*.quantity' => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $rows = collect($data['items'])
            ->filter(fn ($r) => ! empty($r['menu_item_id']) && ! empty($r['quantity']))
            ->values();

        if ($rows->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Minimal 1 item menu harus diisi.'])
                ->withInput();
        }

        $member = null;
        if (! empty($data['member_email'])) {
            $member = User::query()
                ->where('email', $data['member_email'])
                ->where('role', User::ROLE_MEMBER)
                ->first();
        }

        $purchasedAt = isset($data['purchased_at']) && $data['purchased_at']
            ? \Illuminate\Support\Carbon::parse($data['purchased_at'])
            : now();

        try {
            DB::transaction(function () use ($rows, $member, $purchasedAt, $data) {
            $orderNumber = Transaction::nextOrderNumber($data['order_type'], $purchasedAt);

            $menuItems = MenuItem::query()
                ->whereIn('id', $rows->pluck('menu_item_id'))
                ->get()
                ->keyBy('id');

            $subtotal = 0;
            $totalQty = 0;
            $cheapestUnitPrice = null;

            foreach ($rows as $r) {
                $menu = $menuItems[(int) $r['menu_item_id']] ?? null;
                if (! $menu) {
                    continue;
                }

                $qty = (int) $r['quantity'];
                $subtotal += ($menu->price * $qty);
                $totalQty += $qty;

                $price = (int) $menu->price;
                if ($price > 0) {
                    $cheapestUnitPrice = $cheapestUnitPrice === null ? $price : min($cheapestUnitPrice, $price);
                }
            }

            $stampsPerReward = Settings::getInt('loyalty.stamps_per_reward', 5);
            $rewardLabel = Settings::get('loyalty.reward_label', 'Gratis 1 minuman');
            $wantsReward = (bool) ($data['use_reward'] ?? false);
            $availableNow = $member ? $member->availableRewards($stampsPerReward) : 0;
            $useReward = $member && $wantsReward && $availableNow > 0 && (int) ($cheapestUnitPrice ?? 0) > 0;

            $rewardDiscount = $useReward ? (int) $cheapestUnitPrice : 0;

            $promo = null;
            $promoDiscount = 0;
            if (! empty($data['promo_id'])) {
                $promo = Promo::query()->find((int) $data['promo_id']);
                if (! $promo) {
                    throw new \RuntimeException('Promo tidak ditemukan.');
                }
                if (! $promo->isActive($purchasedAt)) {
                    throw new \RuntimeException('Promo sudah tidak aktif.');
                }
                if (! $promo->isEligibleForSubtotal((int) $subtotal)) {
                    throw new \RuntimeException('Promo tidak memenuhi minimal belanja Rp '.number_format((int) $promo->min_subtotal, 0, ',', '.').'.');
                }
                $promoDiscount = $promo->calculateDiscount((int) $subtotal);
            }

            $discount = min((int) $subtotal, $promoDiscount + $rewardDiscount);
            $total = max(0, $subtotal - $discount);

            $trx = Transaction::query()->create([
                'transaction_code' => $this->generateCode($purchasedAt),
                'user_id' => $member?->id,
                'promo_id' => $promo?->id,
                'promo_name_snapshot' => $promo?->name,
                'purchased_at' => $purchasedAt,
                'payment_status' => Transaction::PAYMENT_PAID,
                'payment_method' => $data['payment_method'],
                'paid_at' => now(),
                'order_type' => $data['order_type'],
                'order_number' => $orderNumber,
                'sales_channel' => Transaction::CHANNEL_POS,
                'subtotal' => $subtotal,
                'promo_discount' => $promoDiscount,
                'reward_discount' => $rewardDiscount,
                'reward_redeemed_count' => $useReward ? 1 : 0,
                'discount' => $discount,
                'total' => $total,
                'note' => $data['note'],
            ]);

            foreach ($rows as $r) {
                $menu = $menuItems[(int) $r['menu_item_id']] ?? null;
                if (! $menu) {
                    continue;
                }

                $qty = (int) $r['quantity'];

                TransactionItem::query()->create([
                    'transaction_id' => $trx->id,
                    'menu_item_id' => $menu->id,
                    'menu_name_snapshot' => $menu->name,
                    'unit_price_snapshot' => $menu->price,
                    'quantity' => $qty,
                    'line_total' => $menu->price * $qty,
                ]);
            }

            if ($member && $totalQty > 0) {
                $member->increment('loyalty_stamps', $totalQty);
            }

            if ($useReward) {
                $member->increment('loyalty_redeemed', 1);

                RewardRedemption::query()->create([
                    'user_id' => $member->id,
                    'transaction_id' => $trx->id,
                    'redeemed_at' => $purchasedAt,
                    'stamps_per_reward' => $stampsPerReward,
                    'reward_label' => $rewardLabel ?: 'Gratis 1 minuman',
                    'reward_value' => $rewardDiscount,
                    'note' => 'Redeem via kasir (admin)',
                ]);
            }
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.pos')->with('success', 'Transaksi berhasil dicatat.');
    }

    private function generateCode($dateTime): string
    {
        do {
            $code = 'TRX-'.$dateTime->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Transaction::query()->where('transaction_code', $code)->exists());

        return $code;
    }
}
