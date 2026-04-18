<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->string('discount_type')->default('amount')->after('description'); // amount|percent
            $table->unsignedInteger('discount_value')->default(0)->after('discount_type'); // Rp or %
            $table->unsignedInteger('min_subtotal')->default(0)->after('discount_value'); // minimal belanja (Rp)
        });
    }

    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'min_subtotal']);
        });
    }
};

