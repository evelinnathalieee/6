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

        $transactions = Transaction::query()
            ->with(['user', 'items'])
            ->whereBetween('purchased_at', [$day, $dayEnd])
            ->orderByDesc('purchased_at')
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'date' => $day,
            'total_sales' => (int) Transaction::query()->whereBetween('purchased_at', [$day, $dayEnd])->sum('total'),
            'total_transactions' => (int) Transaction::query()->whereBetween('purchased_at', [$day, $dayEnd])->count(),
        ];

        return view('admin.sales.index', [
            'transactions' => $transactions,
            'summary' => $summary,
        ]);
    }
}

