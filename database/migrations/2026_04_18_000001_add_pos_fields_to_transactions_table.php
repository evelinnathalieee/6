<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('purchased_at'); // pending|paid|canceled
            $table->string('payment_method')->default('cash')->after('payment_status'); // cash|qris
            $table->timestamp('paid_at')->nullable()->after('payment_method');
            $table->timestamp('canceled_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method', 'paid_at', 'canceled_at']);
        });
    }
};

