<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class NotificationsController extends Controller
{
    public function __invoke()
    {
        $today = now()->startOfDay();

        $latest = Transaction::query()
            ->with(['user', 'items'])
            ->orderByDesc('purchased_at')
            ->first();

        $lowStockCount = Ingredient::query()
            ->get()
            ->filter(fn ($i) => $i->stockStatus() !== 'aman')
            ->count();

        return response()->json([
            'server_time' => now()->toIso8601String(),
            'today_sales' => (int) Transaction::query()->where('purchased_at', '>=', $today)->where('payment_status', Transaction::PAYMENT_PAID)->sum('total'),
            'today_transactions' => (int) Transaction::query()->where('purchased_at', '>=', $today)->where('payment_status', Transaction::PAYMENT_PAID)->count(),
            'low_stock_count' => (int) $lowStockCount,
            'latest_transaction' => $latest ? [
                'code' => $latest->transaction_code,
                'order_number' => $latest->order_number,
                'order_type' => $latest->order_type,
                'payment_status' => $latest->payment_status,
                'payment_method' => $latest->payment_method,
                'total' => (int) $latest->total,
                'discount' => (int) $latest->discount,
                'purchased_at' => $latest->purchased_at instanceof Carbon ? $latest->purchased_at->toIso8601String() : null,
                'customer' => $latest->user?->name ?? 'Walk-in',
                'items_count' => $latest->items->sum('quantity'),
            ] : null,
        ]);
    }
}
