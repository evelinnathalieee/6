<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\Promo;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WestlandSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $admin = User::query()->updateOrCreate(
                ['email' => 'admin@westland.test'],
                [
                    'name' => 'Admin Westland',
                    'phone' => '0812-0000-0000',
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_ADMIN,
                    'member_code' => null,
                    'loyalty_stamps' => 0,
                    'loyalty_redeemed' => 0,
                ]
            );

            $members = collect([
                ['name' => 'Aulia Putri', 'email' => 'aulia@member.test', 'phone' => '0813-1111-1111'],
                ['name' => 'Rizky Fadli', 'email' => 'rizky@member.test', 'phone' => '0813-2222-2222'],
                ['name' => 'Nadia Salsabila', 'email' => 'nadia@member.test', 'phone' => '0813-3333-3333'],
            ])->map(function (array $m, int $i) {
                return User::query()->updateOrCreate(
                    ['email' => $m['email']],
                    [
                        'name' => $m['name'],
                        'phone' => $m['phone'],
                        'password' => Hash::make('password'),
                        'role' => User::ROLE_MEMBER,
                        'member_code' => sprintf('WLC-%04d', $i + 1),
                    ]
                );
            });

            $menu = [
                [
                    'name' => 'Es Kopi Susu',
                    'category' => 'kopi',
                    'price' => 18000,
                    'is_featured' => true,
                    'description' => 'Signature: espresso, susu segar, dan gula aren.',
                ],
                [
                    'name' => 'Americano',
                    'category' => 'kopi',
                    'price' => 15000,
                    'is_featured' => true,
                    'description' => 'Espresso + air, ringan dan bold.',
                ],
                [
                    'name' => 'Latte',
                    'category' => 'kopi',
                    'price' => 20000,
                    'is_featured' => false,
                    'description' => 'Espresso creamy dengan susu hangat.',
                ],
                [
                    'name' => 'Matcha Latte',
                    'category' => 'non_kopi',
                    'price' => 22000,
                    'is_featured' => true,
                    'description' => 'Matcha premium dengan susu, lembut dan wangi.',
                ],
                [
                    'name' => 'Chocolate',
                    'category' => 'non_kopi',
                    'price' => 20000,
                    'is_featured' => false,
                    'description' => 'Cokelat rich, cocok untuk nongkrong santai.',
                ],
                [
                    'name' => 'Red Velvet',
                    'category' => 'non_kopi',
                    'price' => 22000,
                    'is_featured' => false,
                    'description' => 'Manis creamy khas red velvet.',
                ],
            ];

            foreach ($menu as $item) {
                MenuItem::query()->updateOrCreate(
                    ['slug' => Str::slug($item['name'])],
                    [
                        ...$item,
                        'slug' => Str::slug($item['name']),
                        'image_url' => null,
                    ]
                );
            }

            $ingredients = [
                ['name' => 'kopi', 'unit' => 'g', 'opening_stock' => 5000, 'low_stock_threshold' => 800],
                ['name' => 'susu', 'unit' => 'ml', 'opening_stock' => 15000, 'low_stock_threshold' => 3000],
                ['name' => 'sirup', 'unit' => 'ml', 'opening_stock' => 6000, 'low_stock_threshold' => 1000],
                ['name' => 'gula', 'unit' => 'g', 'opening_stock' => 8000, 'low_stock_threshold' => 1200],
                ['name' => 'es', 'unit' => 'g', 'opening_stock' => 20000, 'low_stock_threshold' => 4000],
                ['name' => 'cup', 'unit' => 'pcs', 'opening_stock' => 500, 'low_stock_threshold' => 80],
            ];

            foreach ($ingredients as $ing) {
                Ingredient::query()->updateOrCreate(
                    ['name' => $ing['name']],
                    [
                        'unit' => $ing['unit'],
                        'opening_stock' => $ing['opening_stock'],
                        'current_stock' => $ing['opening_stock'],
                        'low_stock_threshold' => $ing['low_stock_threshold'],
                    ]
                );
            }

            // Simulate movements (some stock decreases so admin can see "menipis")
            $this->movement('kopi', 'out', 4300, 'Pemakaian minggu ini', $admin->id, now()->subDays(2));
            $this->movement('cup', 'out', 430, 'Pemakaian minggu ini', $admin->id, now()->subDays(2));
            $this->movement('susu', 'out', 9000, 'Pemakaian minggu ini', $admin->id, now()->subDays(2));
            $this->movement('sirup', 'out', 5200, 'Pemakaian minggu ini', $admin->id, now()->subDays(2));
            $this->movement('gula', 'out', 6000, 'Pemakaian minggu ini', $admin->id, now()->subDays(2));
            $this->movement('es', 'out', 16000, 'Pemakaian minggu ini', $admin->id, now()->subDays(2));
            $this->movement('cup', 'in', 100, 'Stok masuk (supplier)', $admin->id, now()->subDay());

            // Promos
            Promo::query()->updateOrCreate(
                ['slug' => 'nongkrong-hemat'],
                [
                    'name' => 'Nongkrong Hemat',
                    'slug' => 'nongkrong-hemat',
                    'description' => 'Diskon 15% untuk 2 minuman (kopi/non-kopi) setiap hari Senin–Kamis.',
                    'starts_at' => now()->subDays(10),
                    'ends_at' => now()->addDays(20),
                    'is_enabled' => true,
                ]
            );

            Promo::query()->updateOrCreate(
                ['slug' => 'member-double-stamp-weekend'],
                [
                    'name' => 'Member Double Stamp Weekend',
                    'slug' => 'member-double-stamp-weekend',
                    'description' => 'Khusus member: cap double untuk transaksi di hari Sabtu & Minggu.',
                    'starts_at' => now()->addDays(5),
                    'ends_at' => now()->addDays(35),
                    'is_enabled' => true,
                ]
            );

            // Transactions + loyalty stamps (1 stamp per drink)
            $menuItems = MenuItem::query()->get()->keyBy('slug');

            $this->seedTransaction($members[0], now()->subDays(3), [
                [$menuItems['es-kopi-susu'], 2],
                [$menuItems['matcha-latte'], 1],
            ]);

            $this->seedTransaction($members[1], now()->subDays(1), [
                [$menuItems['americano'], 1],
                [$menuItems['latte'], 1],
            ]);

            $this->seedTransaction($members[0], now(), [
                [$menuItems['es-kopi-susu'], 1],
            ]);

            $this->seedTransaction(null, now(), [
                [$menuItems['es-kopi-susu'], 2],
                [$menuItems['chocolate'], 1],
            ]);

            // Make sure members have some stamps for demo UI
            $members[0]->update(['loyalty_stamps' => 4, 'loyalty_redeemed' => 0]);
            $members[1]->update(['loyalty_stamps' => 6, 'loyalty_redeemed' => 1]);
            $members[2]->update(['loyalty_stamps' => 1, 'loyalty_redeemed' => 0]);
        });
    }

    private function movement(string $ingredientName, string $type, float $quantity, string $note, int $createdBy, $movedAt): void
    {
        $ingredient = Ingredient::query()->where('name', $ingredientName)->firstOrFail();

        StockMovement::query()->create([
            'ingredient_id' => $ingredient->id,
            'type' => $type,
            'quantity' => $quantity,
            'note' => $note,
            'moved_at' => $movedAt,
            'created_by' => $createdBy,
        ]);

        $ingredient->update([
            'current_stock' => max(0, (float) $ingredient->current_stock + ($type === 'in' ? $quantity : -$quantity)),
        ]);
    }

    private function seedTransaction(?User $user, $purchasedAt, array $items): void
    {
        $subtotal = 0;
        $totalQty = 0;

        foreach ($items as [$menuItem, $qty]) {
            $subtotal += ($menuItem->price * $qty);
            $totalQty += $qty;
        }

        $discount = 0;
        $total = $subtotal - $discount;

        $trx = Transaction::query()->create([
            'transaction_code' => 'TRX-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'user_id' => $user?->id,
            'purchased_at' => $purchasedAt,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'note' => $user ? 'Transaksi member' : 'Walk-in',
        ]);

        foreach ($items as [$menuItem, $qty]) {
            TransactionItem::query()->create([
                'transaction_id' => $trx->id,
                'menu_item_id' => $menuItem->id,
                'menu_name_snapshot' => $menuItem->name,
                'unit_price_snapshot' => $menuItem->price,
                'quantity' => $qty,
                'line_total' => $menuItem->price * $qty,
            ]);
        }

        if ($user) {
            $user->increment('loyalty_stamps', $totalQty);
        }
    }
}

