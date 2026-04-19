<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardRedemption;
use App\Models\Transaction;
use App\Models\User;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    private function redirectAfterProcess(Transaction $transaction, string $message)
    {
        if ($transaction->sales_channel === Transaction::CHANNEL_ONLINE) {
            return redirect()
                ->route('admin.sales.index', ['channel' => Transaction::CHANNEL_ONLINE])
                ->with('success', $message);
        }

        return redirect()
            ->route('admin.pos')
            ->with('success', $message);
    }

    public function pay(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'payment_method' => ['required', 'in:cash,qris'],
            'use_reward' => ['nullable', 'boolean'],
        ]);

        if ($transaction->payment_status !== Transaction::PAYMENT_PENDING) {
            return back()->with('error', 'Status transaksi sudah diproses.');
        }

        DB::transaction(function () use ($transaction, $data) {
            $transaction->refresh();

            if ($transaction->payment_status !== Transaction::PAYMENT_PENDING) {
                return;
            }

            $stampsPerReward = Settings::getInt('loyalty.stamps_per_reward', 5);
            $rewardLabel = Settings::get('loyalty.reward_label', 'Gratis 1 minuman') ?: 'Gratis 1 minuman';

            $promoDiscount = (int) ($transaction->promo_discount ?? 0);
            $rewardDiscount = 0;
            $rewardRedeemedCount = 0;

            /** @var User|null $member */
            $member = $transaction->user;
            $wantsReward = (bool) ($data['use_reward'] ?? false);

            if ($member && $wantsReward && $member->availableRewards($stampsPerReward) > 0) {
                $cheapest = $transaction->items()->min('unit_price_snapshot');
                $rewardDiscount = max(0, (int) ($cheapest ?? 0));
                $rewardRedeemedCount = $rewardDiscount > 0 ? 1 : 0;
            }

            $subtotal = (int) $transaction->subtotal;
            $discount = min($subtotal, $promoDiscount + $rewardDiscount);
            $total = max(0, $subtotal - $discount);

            $transaction->update([
                'payment_method' => $data['payment_method'],
                'payment_status' => Transaction::PAYMENT_PAID,
                'paid_at' => now(),
                'reward_discount' => $rewardDiscount,
                'reward_redeemed_count' => $rewardRedeemedCount,
                'discount' => $discount,
                'total' => $total,
            ]);

            if ($member) {
                $qty = (int) $transaction->items()->sum('quantity');
                if ($qty > 0) {
                    $member->increment('loyalty_stamps', $qty);
                }

                if ($rewardRedeemedCount > 0) {
                    $member->increment('loyalty_redeemed', $rewardRedeemedCount);

                    RewardRedemption::query()->create([
                        'user_id' => $member->id,
                        'transaction_id' => $transaction->id,
                        'redeemed_at' => now(),
                        'stamps_per_reward' => $stampsPerReward,
                        'reward_label' => $rewardLabel,
                        'reward_value' => $rewardDiscount,
                        'note' => 'Redeem saat pembayaran (POS)',
                    ]);
                }
            }
        });

        return $this->redirectAfterProcess($transaction->fresh(), 'Transaksi dibayar.');
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->payment_status !== Transaction::PAYMENT_PENDING) {
            return back()->with('error', 'Status transaksi sudah diproses.');
        }

        $transaction->update([
            'payment_status' => Transaction::PAYMENT_CANCELED,
            'canceled_at' => now(),
        ]);

        return $this->redirectAfterProcess($transaction->fresh(), 'Transaksi dibatalkan.');
    }
}
