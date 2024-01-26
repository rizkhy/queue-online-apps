<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::truncate();

        $user = User::create([
            'email' => 'admin@admin.com',
            'password' => \Hash::make('123123'),
            'name' => 'Administrator',
            'phone' => '081234567890',
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
