<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Promo;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featuredMenu = MenuItem::query()
            ->where('is_featured', true)
            ->orderBy('name')
            ->get();

        $promos = Promo::query()
            ->where('is_enabled', true)
            ->orderByDesc('starts_at')
            ->get()
            ->filter(fn (Promo $p) => $p->isActive())
            ->take(3)
            ->values();

        return view('pages.home', [
            'featuredMenu' => $featuredMenu,
            'promos' => $promos,
        ]);
    }
}
