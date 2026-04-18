<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = now()->startOfDay();

        $todaySales = (int) Transaction::query()
            ->where('purchased_at', '>=', $today)
            ->where('payment_status', Transaction::PAYMENT_PAID)
            ->sum('total');

        $todayTransactions = (int) Transaction::query()
            ->where('purchased_at', '>=', $today)
            ->where('payment_status', Transaction::PAYMENT_PAID)
            ->count();

        $bestSeller = TransactionItem::query()
            ->select('menu_name_snapshot', DB::raw('SUM(quantity) as qty'))
            ->whereHas('transaction', fn ($q) => $q->where('purchased_at', '>=', $today)->where('payment_status', Transaction::PAYMENT_PAID))
            ->groupBy('menu_name_snapshot')
            ->orderByDesc('qty')
            ->first();

        $membersCount = (int) User::query()->where('role', User::ROLE_MEMBER)->count();

        $lowStock = Ingredient::query()
            ->get()
            ->filter(fn (Ingredient $i) => $i->stockStatus() !== 'aman')
            ->values();

        return view('admin.dashboard', [
            'todaySales' => $todaySales,
            'todayTransactions' => $todayTransactions,
            'bestSeller' => $bestSeller,
            'membersCount' => $membersCount,
            'lowStock' => $lowStock,
        ]);
    }
}
