<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category'); // kopi|non_kopi
            $table->unsignedInteger('price'); // rupiah
            $table->boolean('is_featured')->default(false);
            $table->text('description')->nullable();
            $table->string('image_url')->nullable(); // can be http url or Storage path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
