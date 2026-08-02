<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_admin')->default(false)->after('role_id');
        });

        DB::table('users')
            ->whereIn('role_id', ['0', '1', 0, 1])
            ->update(['is_admin' => true]);

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('role_id');
            $table->dropColumn('google_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role_id')->default('3')->after('email');
            $table->string('google_id')->nullable()->after('phone');
        });

        DB::table('users')
            ->where('is_admin', true)
            ->update(['role_id' => '0']);

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('is_admin');
        });
    }
};
