<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::query()
            ->withSum(['movements as stock_in' => fn ($q) => $q->where('type', 'in')], 'quantity')
            ->withSum(['movements as stock_out' => fn ($q) => $q->where('type', 'out')], 'quantity')
            ->orderBy('name')
            ->get();

        $recentMovements = StockMovement::query()
            ->with('ingredient')
            ->orderByDesc('moved_at')
            ->limit(6)
            ->get();

        $summary = [
            'total_items' => $ingredients->count(),
            'safe_items' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->stockStatus() === 'aman')->count(),
            'low_items' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->stockStatus() === 'menipis')->count(),
            'empty_items' => $ingredients->filter(fn (Ingredient $ingredient) => $ingredient->stockStatus() === 'habis')->count(),
        ];

        return view('admin.stocks.index', [
            'ingredients' => $ingredients,
            'recentMovements' => $recentMovements,
            'summary' => $summary,
        ]);
    }

    public function createIngredient()
    {
        return view('admin.stocks.create-ingredient');
    }

    public function storeIngredient(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:ingredients,name'],
            'unit' => ['required', 'string', 'max:10'],
            'opening_stock' => ['required', 'numeric', 'min:0'],
            'low_stock_threshold' => ['required', 'numeric', 'min:0'],
        ]);

        Ingredient::query()->create([
            ...$data,
            'current_stock' => $data['opening_stock'],
        ]);

        return redirect()->route('admin.stocks.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function editIngredient(Ingredient $ingredient)
    {
        return view('admin.stocks.edit-ingredient', [
            'ingredient' => $ingredient,
        ]);
    }

    public function updateIngredient(Request $request, Ingredient $ingredient)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:ingredients,name,'.$ingredient->id],
            'unit' => ['required', 'string', 'max:10'],
            'low_stock_threshold' => ['required', 'numeric', 'min:0'],
        ]);

        $ingredient->update($data);

        return redirect()->route('admin.stocks.index')->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function createMovement(Ingredient $ingredient)
    {
        return view('admin.stocks.create-movement', [
            'ingredient' => $ingredient,
        ]);
    }

    public function storeMovement(Request $request, Ingredient $ingredient)
    {
        $data = $request->validate([
            'type' => ['required', 'in:in,out,adjust'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'note' => ['nullable', 'string', 'max:500'],
            'moved_at' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($request, $ingredient, $data) {
            StockMovement::query()->create([
                'ingredient_id' => $ingredient->id,
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'note' => $data['note'] ?? null,
                'moved_at' => $data['moved_at'] ?? now(),
                'created_by' => $request->user()->id,
            ]);

            $current = (float) $ingredient->current_stock;
            $qty = (float) $data['quantity'];

            $new = match ($data['type']) {
                'in' => $current + $qty,
                'out' => max(0, $current - $qty),
                'adjust' => max(0, $qty),
            };

            $ingredient->update(['current_stock' => $new]);
        });

        return redirect()->route('admin.stocks.index')->with('success', 'Pergerakan stok tersimpan.');
    }

    public function movements()
    {
        $movements = StockMovement::query()
            ->with(['ingredient', 'creator'])
            ->orderByDesc('moved_at')
            ->paginate(15);

        return view('admin.stocks.movements', [
            'movements' => $movements,
        ]);
    }
}
