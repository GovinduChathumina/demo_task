<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->truncate();

        $faker = \Faker\Factory::create();
        
        foreach (range(1, 20) as $index) {
            DB::table('tasks')->insert([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'status' => $faker->randomElement(['completed', 'uncompleted']),
                'due_date' => $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
