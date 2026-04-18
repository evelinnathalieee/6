<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $category = $request->string('category')->toString();

        $menu = MenuItem::query()
            ->when($q, fn ($query) => $query->where('name', 'like', '%'.$q.'%'))
            ->when($category, fn ($query) => $query->where('category', $category))
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.menu.index', [
            'menu' => $menu,
            'q' => $q,
            'category' => $category,
        ]);
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'in:kopi,non_kopi'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $slugBase = Str::slug($data['name']);
        $slug = $slugBase;
        $i = 2;
        while (MenuItem::query()->where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$i;
            $i++;
        }

        $imagePath = $request->file('image')->storePublicly('menu', 'public');

        MenuItem::query()->create([
            ...$data,
            'slug' => $slug,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
            'image_url' => $imagePath,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(MenuItem $menuItem)
    {
        return view('admin.menu.edit', [
            'menuItem' => $menuItem,
        ]);
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'in:kopi,non_kopi'],
            'price' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($menuItem->image_url && ! Str::startsWith($menuItem->image_url, ['http://', 'https://'])) {
                Storage::disk('public')->delete($menuItem->image_url);
            }

            $menuItem->image_url = $request->file('image')->storePublicly('menu', 'public');
        }

        if (! $menuItem->image_url) {
            return back()->withErrors(['image' => 'Gambar wajib diisi.'])->withInput();
        }

        $menuItem->update([
            ...$data,
            'is_featured' => (bool) ($data['is_featured'] ?? false),
            'image_url' => $menuItem->image_url,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image_url && ! Str::startsWith($menuItem->image_url, ['http://', 'https://'])) {
            Storage::disk('public')->delete($menuItem->image_url);
        }

        $menuItem->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus.');
    }
}
