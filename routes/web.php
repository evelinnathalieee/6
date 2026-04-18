<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\Site\HomeController::class)->name('home');
Route::get('/menu', \App\Http\Controllers\Site\MenuController::class)->name('menu');
Route::get('/promo', \App\Http\Controllers\Site\PromoController::class)->name('promos');
Route::get('/loyalty', \App\Http\Controllers\Site\LoyaltyController::class)->name('loyalty');

// Cart requires member login.
Route::middleware('role:member')->group(function () {
    Route::get('/cart', [\App\Http\Controllers\Member\CartController::class, 'show'])->name('cart.show');
    Route::post('/cart/add/{menuItem}', [\App\Http\Controllers\Member\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [\App\Http\Controllers\Member\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [\App\Http\Controllers\Member\CartController::class, 'clear'])->name('cart.clear');
});

Route::middleware('guest')->group(function () {
    // Unified auth (member + admin)
    Route::get('/register', [\App\Http\Controllers\Auth\MemberAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\MemberAuthController::class, 'register'])->name('register.store');
    Route::get('/login', [\App\Http\Controllers\Auth\MemberAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\MemberAuthController::class, 'login'])->name('login.store');

    // Backward-compatible URLs (no longer linked in UI)
    Route::get('/member/register', fn () => redirect()->route('register'))->name('member.register');
    Route::post('/member/register', fn () => redirect()->route('register.store'))->name('member.register.store');
    Route::get('/member/login', fn () => redirect()->route('login'))->name('member.login');
    Route::post('/member/login', fn () => redirect()->route('login.store'))->name('member.login.store');
    Route::get('/admin/login', fn () => redirect()->route('login'))->name('admin.login');
    Route::post('/admin/login', fn () => redirect()->route('login.store'))->name('admin.login.store');
});

Route::prefix('member')->middleware('role:member')->group(function () {
    Route::get('/', \App\Http\Controllers\Member\ProfileController::class)->name('member.profile');
    Route::get('/profile/edit', [\App\Http\Controllers\Member\ProfileEditController::class, 'edit'])->name('member.profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Member\ProfileEditController::class, 'update'])->name('member.profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Member\ProfileEditController::class, 'password'])->name('member.profile.password');
    Route::get('/transactions', \App\Http\Controllers\Member\TransactionsController::class)->name('member.transactions');
    Route::get('/rewards', \App\Http\Controllers\Member\RewardsController::class)->name('member.rewards');
    Route::get('/checkout', [\App\Http\Controllers\Member\CheckoutController::class, 'show'])->name('member.checkout');
    Route::post('/checkout/pay', [\App\Http\Controllers\Member\CheckoutController::class, 'pay'])->name('member.checkout.pay');
    Route::post('/logout', [\App\Http\Controllers\Auth\MemberAuthController::class, 'logout'])->name('member.logout');
});

Route::prefix('admin')->middleware('role:admin')->group(function () {
    Route::get('/', \App\Http\Controllers\Admin\DashboardController::class)->name('admin.dashboard');
    Route::post('/logout', [\App\Http\Controllers\Auth\AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/notifications', \App\Http\Controllers\Admin\NotificationsController::class)->name('admin.notifications');

    Route::get('/orders', [\App\Http\Controllers\Admin\OrdersController::class, 'index'])->name('admin.orders.index');
    Route::get('/pos', [\App\Http\Controllers\Admin\PosScreenController::class, 'show'])->name('admin.pos');

    Route::get('/loyalty', [\App\Http\Controllers\Admin\LoyaltySettingsController::class, 'edit'])->name('admin.loyalty.edit');
    Route::put('/loyalty', [\App\Http\Controllers\Admin\LoyaltySettingsController::class, 'update'])->name('admin.loyalty.update');
    Route::get('/loyalty/redemptions', [\App\Http\Controllers\Admin\RewardRedemptionsController::class, 'index'])->name('admin.loyalty.redemptions');

    Route::get('/menu', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('admin.menu.index');
    Route::get('/menu/create', [\App\Http\Controllers\Admin\MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/menu', [\App\Http\Controllers\Admin\MenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/menu/{menuItem}/edit', [\App\Http\Controllers\Admin\MenuController::class, 'edit'])->name('admin.menu.edit');
    Route::put('/menu/{menuItem}', [\App\Http\Controllers\Admin\MenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/menu/{menuItem}', [\App\Http\Controllers\Admin\MenuController::class, 'destroy'])->name('admin.menu.destroy');

    Route::get('/stocks', [\App\Http\Controllers\Admin\StockController::class, 'index'])->name('admin.stocks.index');
    Route::get('/stocks/movements', [\App\Http\Controllers\Admin\StockController::class, 'movements'])->name('admin.stocks.movements');
    Route::get('/stocks/create', [\App\Http\Controllers\Admin\StockController::class, 'createIngredient'])->name('admin.stocks.create');
    Route::post('/stocks', [\App\Http\Controllers\Admin\StockController::class, 'storeIngredient'])->name('admin.stocks.store');
    Route::get('/stocks/{ingredient}/edit', [\App\Http\Controllers\Admin\StockController::class, 'editIngredient'])->name('admin.stocks.edit');
    Route::put('/stocks/{ingredient}', [\App\Http\Controllers\Admin\StockController::class, 'updateIngredient'])->name('admin.stocks.update');
    Route::get('/stocks/{ingredient}/movement', [\App\Http\Controllers\Admin\StockController::class, 'createMovement'])->name('admin.stocks.movement.create');
    Route::post('/stocks/{ingredient}/movement', [\App\Http\Controllers\Admin\StockController::class, 'storeMovement'])->name('admin.stocks.movement.store');

    Route::get('/sales', [\App\Http\Controllers\Admin\SalesController::class, 'index'])->name('admin.sales.index');
    Route::get('/sales/{transaction}', [\App\Http\Controllers\Admin\SalesShowController::class, 'show'])->name('admin.sales.show');
    Route::get('/sales/create', [\App\Http\Controllers\Admin\TransactionController::class, 'create'])->name('admin.sales.create');
    Route::post('/sales', [\App\Http\Controllers\Admin\TransactionController::class, 'store'])->name('admin.sales.store');
    Route::post('/sales/{transaction}/pay', [\App\Http\Controllers\Admin\PosController::class, 'pay'])->name('admin.sales.pay');
    Route::post('/sales/{transaction}/cancel', [\App\Http\Controllers\Admin\PosController::class, 'cancel'])->name('admin.sales.cancel');

    Route::get('/promos', [\App\Http\Controllers\Admin\PromoController::class, 'index'])->name('admin.promos.index');
    Route::get('/promos/create', [\App\Http\Controllers\Admin\PromoController::class, 'create'])->name('admin.promos.create');
    Route::post('/promos', [\App\Http\Controllers\Admin\PromoController::class, 'store'])->name('admin.promos.store');
    Route::get('/promos/{promo}/edit', [\App\Http\Controllers\Admin\PromoController::class, 'edit'])->name('admin.promos.edit');
    Route::put('/promos/{promo}', [\App\Http\Controllers\Admin\PromoController::class, 'update'])->name('admin.promos.update');

    Route::get('/members', [\App\Http\Controllers\Admin\MemberController::class, 'index'])->name('admin.members.index');
});
