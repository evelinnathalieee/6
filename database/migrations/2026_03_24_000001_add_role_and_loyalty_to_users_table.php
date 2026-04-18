<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('member')->after('password'); // member|admin
            $table->string('phone')->nullable()->after('email');
            $table->string('member_code')->nullable()->unique()->after('phone');
            $table->unsignedInteger('loyalty_stamps')->default(0)->after('remember_token');
            $table->unsignedInteger('loyalty_redeemed')->default(0)->after('loyalty_stamps');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'member_code',
                'loyalty_stamps',
                'loyalty_redeemed',
            ]);
        });
    }
};

