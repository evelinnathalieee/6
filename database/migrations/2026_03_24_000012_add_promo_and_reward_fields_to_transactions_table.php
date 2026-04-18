<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('promo_id')->nullable()->after('user_id')->constrained('promos')->nullOnDelete();
            $table->string('promo_name_snapshot')->nullable()->after('promo_id');
            $table->unsignedInteger('promo_discount')->default(0)->after('subtotal');
            $table->unsignedInteger('reward_discount')->default(0)->after('promo_discount');
            $table->unsignedInteger('reward_redeemed_count')->default(0)->after('reward_discount');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('promo_id');
            $table->dropColumn(['promo_name_snapshot', 'promo_discount', 'reward_discount', 'reward_redeemed_count']);
        });
    }
};

