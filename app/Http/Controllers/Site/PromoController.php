<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Promo;

class PromoController extends Controller
{
    public function __invoke()
    {
        $now = now();

        $active = Promo::query()
            ->where('is_enabled', true)
            ->orderByDesc('starts_at')
            ->get()
            ->filter(fn (Promo $p) => $p->isActive($now))
            ->values();

        return view('pages.promos', [
            'activePromos' => $active,
        ]);
    }
}
