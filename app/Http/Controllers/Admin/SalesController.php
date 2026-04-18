<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->string('date')->toString();
        $day = $date ? Carbon::parse($date)->startOfDay() : now()->startOfDay();
        $dayEnd = (clone $day)->endOfDay();
        $channel = $request->string('channel')->toString() === Transaction::CHANNEL_ONLINE
            ? Transaction::CHANNEL_ONLINE
            : Transaction::CHANNEL_POS;

        $transactions = Transaction::query()
            ->with(['user', 'items'])
            ->where('sales_channel', $channel)
            ->where('payment_status', Transaction::PAYMENT_PAID)
            ->whereBetween('purchased_at', [$day, $dayEnd])
            ->orderByDesc('purchased_at')
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'date' => $day,
            'channel' => $channel,
            'total_sales' => (int) Transaction::query()
                ->where('sales_channel', $channel)
                ->whereBetween('purchased_at', [$day, $dayEnd])
                ->where('payment_status', Transaction::PAYMENT_PAID)
                ->sum('total'),
            'total_transactions' => (int) Transaction::query()
                ->where('sales_channel', $channel)
                ->whereBetween('purchased_at', [$day, $dayEnd])
                ->where('payment_status', Transaction::PAYMENT_PAID)
                ->count(),
            'pending_transactions' => (int) Transaction::query()
                ->where('sales_channel', $channel)
                ->whereBetween('purchased_at', [$day, $dayEnd])
                ->where('payment_status', Transaction::PAYMENT_PENDING)
                ->count(),
        ];

        return view('admin.sales.index', [
            'transactions' => $transactions,
            'summary' => $summary,
        ]);
    }
}
