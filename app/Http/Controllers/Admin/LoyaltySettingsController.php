<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Settings;
use Illuminate\Http\Request;

class LoyaltySettingsController extends Controller
{
    public function edit()
    {
        return view('admin.loyalty.edit', [
            'stampsPerReward' => Settings::getInt('loyalty.stamps_per_reward', 5),
            'rewardLabel' => Settings::get('loyalty.reward_label', 'Gratis 1 minuman'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'stamps_per_reward' => ['required', 'integer', 'min:1', 'max:50'],
            'reward_label' => ['required', 'string', 'max:80'],
        ]);

        Settings::putInt('loyalty.stamps_per_reward', (int) $data['stamps_per_reward']);
        Settings::put('loyalty.reward_label', (string) $data['reward_label']);

        return redirect()->route('admin.loyalty.edit')->with('success', 'Pengaturan loyalty diperbarui.');
    }
}

