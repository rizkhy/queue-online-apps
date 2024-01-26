<?php

namespace Database\Seeders;

use App\Models\Queue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Queue::truncate();

        for ($i = 1; $i <= 10; $i++) {
            Queue::create([
                'customer_name' => 'Customer ' . $i,
                'queue_number' => 'A00' . $i,
                'status' => 'waiting',
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
