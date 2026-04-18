<?php

namespace App\Http\Controllers\Member;

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

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        [$lines, $total] = CartController::cartSummary($request);

        if (count($lines) === 0) {
            return redirect()->route('cart.show')->with('error', 'Keranjang masih kosong.');
        }

        $totalQty = 0;
        $cheapestUnitPrice = null;

        foreach ($lines as $line) {
            /** @var MenuItem $item */
            $item = $line['item'];
            $qty = (int) $line['qty'];
            $totalQty += $qty;

            $price = (int) $item->price;
            if ($price > 0) {
                $cheapestUnitPrice = $cheapestUnitPrice === null ? $price : min($cheapestUnitPrice, $price);
            }
        }

        $stampsPerReward = Settings::getInt('loyalty.stamps_per_reward', 5);
        $rewardLabel = Settings::get('loyalty.reward_label', 'Gratis 1 minuman');
        $availableRewardsNow = $user->availableRewards($stampsPerReward);
        $rewardValue = (int) ($cheapestUnitPrice ?? 0);
        $canUseReward = $rewardValue > 0 && $availableRewardsNow > 0;

        $now = now();
        $promos = Promo::query()
            ->orderByDesc('starts_at')
            ->limit(30)
            ->get()
            ->map(function (Promo $p) use ($now, $total) {
                $isActive = $p->isActive($now);
                $eligible = $p->isEligibleForSubtotal($total);

                $selectable = $isActive && $eligible;
                $reason = null;

                if (! $p->is_enabled) {
                    $reason = 'Promo tidak aktif';
                } elseif (! $isActive) {
                    if ($p->starts_at && $now->lt($p->starts_at)) {
                        $reason = 'Mulai '.$p->starts_at->format('d M Y H:i');
                    } elseif ($p->ends_at && $now->gt($p->ends_at)) {
                        $reason = 'Sudah berakhir';
                    } else {
                        $reason = 'Tidak tersedia';
                    }
                } elseif (! $eligible) {
                    $reason = 'Min Rp '.number_format((int) $p->min_subtotal, 0, ',', '.');
                }

                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'discount_type' => $p->discount_type,
                    'discount_value' => (int) $p->discount_value,
                    'min_subtotal' => (int) $p->min_subtotal,
                    'label' => $p->discountLabel(),
                    'selectable' => $selectable,
                    'reason' => $reason,
                    'discount_preview' => $selectable ? $p->calculateDiscount($total) : 0,
                ];
            })
            ->values();

        return view('member.checkout', [
            'lines' => $lines,
            'total' => $total,
            'stampsPerReward' => $stampsPerReward,
            'availableRewardsNow' => $availableRewardsNow,
            'rewardValue' => $rewardValue,
            'canUseReward' => $canUseReward,
            'rewardLabel' => $rewardLabel,
            'promos' => $promos,
        ]);
    }

    /**
     * Simulasi pembayaran: klik "Bayar" -> transaksi tercatat sebagai paid (purchased_at sekarang).
     * Tanpa integrasi payment gateway (sesuai kebutuhan tugas).
     */
    public function pay(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'order_type' => ['required', 'in:dine_in,take_away'],
            'use_reward' => ['nullable', 'boolean'],
            'promo_id' => ['nullable', 'integer', 'exists:promos,id'],
        ]);

        [$lines, $total] = CartController::cartSummary($request);

        if (count($lines) === 0) {
            return redirect()->route('cart.show')->with('error', 'Keranjang masih kosong.');
        }

        try {
            DB::transaction(function () use ($request, $user, $lines, $total, $data) {
            $purchasedAt = now();
            $orderNumber = Transaction::nextOrderNumber($request->input('order_type'), $purchasedAt);

            $totalQty = 0;
            $cheapestUnitPrice = null;
            foreach ($lines as $line) {
                /** @var MenuItem $item */
                $item = $line['item'];
                $qty = (int) $line['qty'];
                $totalQty += $qty;

                $price = (int) $item->price;
                if ($price > 0) {
                    $cheapestUnitPrice = $cheapestUnitPrice === null ? $price : min($cheapestUnitPrice, $price);
                }
            }

            $stampsPerReward = Settings::getInt('loyalty.stamps_per_reward', 5);
            $rewardLabel = Settings::get('loyalty.reward_label', 'Gratis 1 minuman');
            $availableNow = $user->availableRewards($stampsPerReward);
            $wantsReward = (bool) ($data['use_reward'] ?? false);
            $useReward = $wantsReward && $availableNow > 0 && (int) ($cheapestUnitPrice ?? 0) > 0;

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
                if (! $promo->isEligibleForSubtotal((int) $total)) {
                    throw new \RuntimeException('Promo tidak memenuhi minimal belanja Rp '.number_format((int) $promo->min_subtotal, 0, ',', '.').'.');
                }
                $promoDiscount = $promo->calculateDiscount((int) $total);
            }

            $discount = min((int) $total, $promoDiscount + $rewardDiscount);
            $grandTotal = max(0, (int) $total - $discount);

            $trx = Transaction::query()->create([
                'transaction_code' => $this->generateCode($purchasedAt),
                'user_id' => $user->id,
                'promo_id' => $promo?->id,
                'promo_name_snapshot' => $promo?->name,
                'purchased_at' => $purchasedAt,
                'order_type' => $request->input('order_type'),
                'order_number' => $orderNumber,
                'subtotal' => $total,
                'promo_discount' => $promoDiscount,
                'reward_discount' => $rewardDiscount,
                'reward_redeemed_count' => $useReward ? 1 : 0,
                'discount' => $discount,
                'total' => $grandTotal,
                'note' => trim(implode(' • ', array_values(array_filter([
                    $promo ? 'Promo: '.$promo->name : null,
                    $useReward ? 'Pakai reward' : null,
                    'Checkout member (simulasi)',
                ])))),
            ]);

            foreach ($lines as $line) {
                /** @var MenuItem $item */
                $item = $line['item'];
                $qty = (int) $line['qty'];

                TransactionItem::query()->create([
                    'transaction_id' => $trx->id,
                    'menu_item_id' => $item->id,
                    'menu_name_snapshot' => $item->name,
                    'unit_price_snapshot' => $item->price,
                    'quantity' => $qty,
                    'line_total' => $item->price * $qty,
                ]);
            }

            if ($totalQty > 0) {
                $user->increment('loyalty_stamps', $totalQty);
            }

            if ($useReward) {
                $user->increment('loyalty_redeemed', 1);

                RewardRedemption::query()->create([
                    'user_id' => $user->id,
                    'transaction_id' => $trx->id,
                    'redeemed_at' => $purchasedAt,
                    'stamps_per_reward' => $stampsPerReward,
                    'reward_label' => $rewardLabel ?: 'Gratis 1 minuman',
                    'reward_value' => $rewardDiscount,
                    'note' => 'Redeem saat checkout member',
                ]);
            }

            $request->session()->forget('cart');
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('member.transactions')->with('success', 'Pembayaran berhasil.');
    }

    private function generateCode($dateTime): string
    {
        do {
            $code = 'TRX-'.$dateTime->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Transaction::query()->where('transaction_code', $code)->exists());

        return $code;
    }
}
