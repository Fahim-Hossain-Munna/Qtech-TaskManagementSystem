<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $statuses = ['pending', 'in_progress', 'completed'];

        // সব user ID collect করা
        $userIds = User::pluck('id')->toArray();

        for ($i = 1; $i <= 50; $i++) {
            DB::table('tasks')->insert([
                'user_id' => $faker->randomElement($userIds),
                'title' => $faker->sentence(4),
                'description' => $faker->paragraph(),
                'status' => $faker->randomElement($statuses),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
