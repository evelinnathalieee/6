<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $transactions = Transaction::query()
            ->where('user_id', $user->id)
            ->with('items')
            ->orderByDesc('purchased_at')
            ->paginate(10);

        return view('member.transactions', [
            'transactions' => $transactions,
        ]);
    }
}

