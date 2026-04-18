<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\Promo;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('westland:make-admin', function () {
    $this->info('Membuat akun admin Westland Coffee');

    $name = $this->ask('Nama admin', 'Admin Westland');
    $email = $this->ask('Email admin', 'admin@westland.local');
    $password = $this->secret('Password admin (min 6 karakter)');

    if (! is_string($password) || strlen($password) < 6) {
        $this->error('Password minimal 6 karakter.');

        return 1;
    }

    $admin = User::query()->updateOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN,
            'member_code' => null,
        ]
    );

    $this->info('Admin siap digunakan:');
    $this->line(' - ID: '.$admin->id);
    $this->line(' - Email: '.$admin->email);

    return 0;
})->purpose('Create or update an admin account for Westland Coffee');

Artisan::command('westland:reset-admin-password', function () {
    $this->info('Reset password admin Westland Coffee');

    $email = $this->ask('Email admin', 'admin@westland.local');

    /** @var User|null $admin */
    $admin = User::query()
        ->where('email', $email)
        ->where('role', User::ROLE_ADMIN)
        ->first();

    if (! $admin) {
        $this->error('Admin dengan email tersebut tidak ditemukan.');
        $this->line('Tips: cek email admin atau buat/overwrite admin via: php artisan westland:make-admin');

        return 1;
    }

    $password = $this->secret('Password baru (min 6 karakter)');
    $confirm = $this->secret('Ulangi password baru');

    if (! is_string($password) || strlen($password) < 6) {
        $this->error('Password minimal 6 karakter.');

        return 1;
    }

    if ($password !== $confirm) {
        $this->error('Konfirmasi password tidak sama.');

        return 1;
    }

    $admin->update([
        'password' => Hash::make($password),
    ]);

    $this->info('Password admin berhasil direset untuk: '.$admin->email);

    return 0;
})->purpose('Reset password for an existing admin account');

Artisan::command('westland:seed-initial', function () {
    $this->info('Mengisi data awal Westland Coffee (menu, bahan baku, promo)');

    $menu = [
        ['name' => 'Es Kopi Susu', 'category' => 'kopi', 'price' => 18000, 'is_featured' => true, 'description' => 'Signature: espresso, susu segar, dan gula aren.'],
        ['name' => 'Americano', 'category' => 'kopi', 'price' => 15000, 'is_featured' => true, 'description' => 'Espresso + air, ringan dan bold.'],
        ['name' => 'Latte', 'category' => 'kopi', 'price' => 20000, 'is_featured' => false, 'description' => 'Espresso creamy dengan susu hangat.'],
        ['name' => 'Matcha Latte', 'category' => 'non_kopi', 'price' => 22000, 'is_featured' => true, 'description' => 'Matcha premium dengan susu, lembut dan wangi.'],
        ['name' => 'Chocolate', 'category' => 'non_kopi', 'price' => 20000, 'is_featured' => false, 'description' => 'Cokelat rich, cocok untuk nongkrong santai.'],
        ['name' => 'Red Velvet', 'category' => 'non_kopi', 'price' => 22000, 'is_featured' => false, 'description' => 'Manis creamy khas red velvet.'],
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

    Promo::query()->updateOrCreate(
        ['slug' => 'nongkrong-hemat'],
        [
            'name' => 'Nongkrong Hemat',
            'slug' => 'nongkrong-hemat',
            'description' => 'Diskon 15% untuk 2 minuman (kopi/non-kopi) setiap hari Senin–Kamis.',
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->addDays(30),
            'is_enabled' => true,
        ]
    );

    $this->info('Selesai. Kamu sekarang bisa lihat menu/promo di landing page.');

    return 0;
})->purpose('Seed initial menu/ingredients/promo for Westland Coffee');
