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
            'promo_id' => ['nullable', 'integer', 'exists:promos,id'],
            'payment_method' => ['required', 'in:cash,qris'],
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

            // Loyalty/reward dicatat saat admin menandai transaksi PAID.

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

            $discount = min((int) $total, $promoDiscount);
            $grandTotal = max(0, (int) $total - $discount);

            $trx = Transaction::query()->create([
                'transaction_code' => $this->generateCode($purchasedAt),
                'user_id' => $user->id,
                'promo_id' => $promo?->id,
                'promo_name_snapshot' => $promo?->name,
                'purchased_at' => $purchasedAt,
                'payment_status' => Transaction::PAYMENT_PENDING,
                'payment_method' => $data['payment_method'],
                'order_type' => $request->input('order_type'),
                'order_number' => $orderNumber,
                'sales_channel' => Transaction::CHANNEL_ONLINE,
                'subtotal' => $total,
                'promo_discount' => $promoDiscount,
                'reward_discount' => 0,
                'reward_redeemed_count' => 0,
                'discount' => $discount,
                'total' => $grandTotal,
                'note' => trim(implode(' • ', array_values(array_filter([
                    $promo ? 'Promo: '.$promo->name : null,
                    'Checkout member',
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

            // POS flow: transaksi member dibuat sebagai PENDING sampai admin konfirmasi sudah dibayar.
            // Stamp & redeem reward dicatat saat admin menandai transaksi PAID (biar tidak bisa iseng order tanpa bayar).

            $request->session()->forget('cart');
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('member.transactions')->with('success', 'Pesanan dibuat.');
    }

    private function generateCode($dateTime): string
    {
        do {
            $code = 'TRX-'.$dateTime->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Transaction::query()->where('transaction_code', $code)->exists());

        return $code;
    }
}
