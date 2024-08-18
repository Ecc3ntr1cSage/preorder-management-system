<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@preorder.hopexito.com',
            'password' => bcrypt('meorkacak'),
            'role_id' => 0,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@preorder.hopexito.com',
            'password' => bcrypt('181d12b7a9A'),
            'role_id' => 1,
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'name' => 'Poster',
            'email' => 'poster@gmail.com',
            'password' => bcrypt('123'),
            'role_id' => 2,
            'email_verified_at' => now(),
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'earning' => 0,
            'balance' => 0,
        ]);
    }
}
