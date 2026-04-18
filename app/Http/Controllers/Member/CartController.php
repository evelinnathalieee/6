<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request)
    {
        [$lines, $total] = $this->cartSummary($request);

        return view('member.cart', [
            'lines' => $lines,
            'total' => $total,
        ]);
    }

    public function add(Request $request, MenuItem $menuItem)
    {
        $qty = (int) $request->input('qty', 1);
        $qty = max(1, min(99, $qty));

        $cart = $request->session()->get('cart', []);
        $cart[$menuItem->id] = ($cart[$menuItem->id] ?? 0) + $qty;
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'integer', 'min:0', 'max:99'],
        ]);

        $cart = [];
        foreach ($data['items'] as $menuId => $qty) {
            $menuId = (int) $menuId;
            $qty = (int) $qty;
            if ($menuId > 0 && $qty > 0) {
                $cart[$menuId] = $qty;
            }
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.show')->with('success', 'Keranjang diperbarui.');
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart');

        return redirect()->route('cart.show')->with('success', 'Keranjang dikosongkan.');
    }

    public static function cartSummary(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $ids = array_keys($cart);

        $items = MenuItem::query()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        $lines = [];
        $total = 0;

        foreach ($cart as $id => $qty) {
            $item = $items[(int) $id] ?? null;
            if (! $item) {
                continue;
            }

            $qty = (int) $qty;
            $lineTotal = $item->price * $qty;
            $total += $lineTotal;

            $lines[] = [
                'item' => $item,
                'qty' => $qty,
                'lineTotal' => $lineTotal,
            ];
        }

        return [$lines, $total];
    }
}

