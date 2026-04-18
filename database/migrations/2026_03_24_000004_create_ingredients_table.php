<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // kopi, susu, ...
            $table->string('unit')->default('pcs'); // g|ml|pcs
            $table->decimal('opening_stock', 12, 2)->default(0);
            $table->decimal('current_stock', 12, 2)->default(0);
            $table->decimal('low_stock_threshold', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};

