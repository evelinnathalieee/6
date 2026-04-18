<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->string('date')->toString();
        $day = $date ? Carbon::parse($date)->startOfDay() : now()->startOfDay();
        $dayEnd = (clone $day)->endOfDay();

        $pendingOrders = Transaction::query()
            ->with(['user', 'items'])
            ->where('sales_channel', Transaction::CHANNEL_ONLINE)
            ->where('payment_status', Transaction::PAYMENT_PENDING)
            ->orderBy('purchased_at')
            ->get();

        $processedOrders = Transaction::query()
            ->with(['user', 'items'])
            ->where('sales_channel', Transaction::CHANNEL_ONLINE)
            ->whereBetween('purchased_at', [$day, $dayEnd])
            ->whereIn('payment_status', [Transaction::PAYMENT_PAID, Transaction::PAYMENT_CANCELED])
            ->orderByDesc('purchased_at')
            ->limit(12)
            ->get();

        return view('admin.orders.index', [
            'pendingOrders' => $pendingOrders,
            'processedOrders' => $processedOrders,
            'selectedDate' => $day,
        ]);
    }
}
