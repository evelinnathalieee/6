<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardRedemption;

class RewardRedemptionsController extends Controller
{
    public function index()
    {
        $redemptions = RewardRedemption::query()
            ->with(['user', 'transaction'])
            ->orderByDesc('redeemed_at')
            ->paginate(20);

        return view('admin.loyalty.redemptions', [
            'redemptions' => $redemptions,
        ]);
    }
}

