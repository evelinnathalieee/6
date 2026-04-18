<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Support\Settings;

class ProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $stampsPerReward = Settings::getInt('loyalty.stamps_per_reward', 5);
        $availableRewards = $user->availableRewards($stampsPerReward);

        return view('member.profile', [
            'user' => $user,
            'stampsPerReward' => $stampsPerReward,
            'availableRewards' => $availableRewards,
        ]);
    }
}
