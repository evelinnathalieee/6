<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Settings;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::query()
            ->where('role', User::ROLE_MEMBER)
            ->withCount('transactions')
            ->orderByDesc('transactions_count')
            ->paginate(15);

        return view('admin.members.index', [
            'members' => $members,
            'stampsPerReward' => Settings::getInt('loyalty.stamps_per_reward', 5),
        ]);
    }
}
