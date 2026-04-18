<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Support\Settings;

class SalesShowController extends Controller
{
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items', 'promo']);

        return view('admin.sales.show', [
            'transaction' => $transaction,
            'stampsPerReward' => Settings::getInt('loyalty.stamps_per_reward', 5),
        ]);
    }
}

