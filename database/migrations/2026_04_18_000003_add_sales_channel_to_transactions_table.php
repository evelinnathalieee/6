<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('sales_channel', 20)->default('pos')->after('order_number');
        });

        DB::table('transactions')
            ->where(function ($query) {
                $query->where('payment_status', 'pending')
                    ->orWhere('note', 'like', '%Checkout member%');
            })
            ->update(['sales_channel' => 'online']);
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('sales_channel');
        });
    }
};
