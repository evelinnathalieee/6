<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Promo;
use Illuminate\Http\Request;

class PosScreenController extends Controller
{
    public function show(Request $request)
    {
        $menu = MenuItem::query()
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $now = now();
        $promos = Promo::query()
            ->orderByDesc('starts_at')
            ->get()
            ->filter(fn (Promo $p) => $p->isActive($now))
            ->values();

        return view('admin.pos', [
            'menu' => $menu,
            'promos' => $promos,
        ]);
    }
}
