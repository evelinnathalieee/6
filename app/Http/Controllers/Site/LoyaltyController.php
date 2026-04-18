<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Support\Settings;

class LoyaltyController extends Controller
{
    public function __invoke()
    {
        return view('pages.loyalty', [
            'stampsPerReward' => Settings::getInt('loyalty.stamps_per_reward', 5),
        ]);
    }
}
