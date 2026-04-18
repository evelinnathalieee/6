<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function __invoke()
    {
        $menu = MenuItem::query()
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('pages.menu', [
            'menuByCategory' => $menu,
        ]);
    }
}
