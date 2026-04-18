<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::query()->orderByDesc('starts_at')->paginate(15);

        return view('admin.promos.index', ['promos' => $promos]);
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1000'],
            'discount_type' => ['required', 'in:amount,percent'],
            'discount_value' => ['required', 'integer', 'min:0', 'max:100000000'],
            'min_subtotal' => ['required', 'integer', 'min:0', 'max:100000000'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'is_enabled' => ['required', 'in:0,1'],
        ]);

        if ($data['discount_type'] === 'percent' && (int) $data['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'Diskon persen maksimal 100.'])->withInput();
        }

        Promo::query()->create([
            ...$data,
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(4)),
            'min_subtotal' => (int) $data['min_subtotal'],
            'is_enabled' => (bool) ((int) $data['is_enabled']),
        ]);

        return redirect()->route('admin.promos.index')->with('success', 'Promo dibuat.');
    }

    public function edit(Promo $promo)
    {
        return view('admin.promos.edit', ['promo' => $promo]);
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1000'],
            'discount_type' => ['required', 'in:amount,percent'],
            'discount_value' => ['required', 'integer', 'min:0', 'max:100000000'],
            'min_subtotal' => ['required', 'integer', 'min:0', 'max:100000000'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after_or_equal:starts_at'],
            'is_enabled' => ['required', 'in:0,1'],
        ]);

        if ($data['discount_type'] === 'percent' && (int) $data['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'Diskon persen maksimal 100.'])->withInput();
        }

        $promo->update([
            ...$data,
            'min_subtotal' => (int) $data['min_subtotal'],
            'is_enabled' => (bool) ((int) $data['is_enabled']),
        ]);

        return redirect()->route('admin.promos.index')->with('success', 'Promo diperbarui.');
    }
}
