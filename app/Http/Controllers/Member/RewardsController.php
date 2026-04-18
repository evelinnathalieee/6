<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Support\Settings;

class RewardsController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $stampsPerReward = Settings::getInt('loyalty.stamps_per_reward', 5);

        return view('member.rewards', [
            'user' => $user,
            'stampsPerReward' => $stampsPerReward,
            'availableRewards' => $user->availableRewards($stampsPerReward),
            'redemptions' => $user->rewardRedemptions()->orderByDesc('redeemed_at')->paginate(10),
        ]);
    }
}
