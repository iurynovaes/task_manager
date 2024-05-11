<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create 3 buildings
        for ($i=1; $i <= 3; $i++) {

            $building =  Building::create([
                'manager' => $faker->firstName . ' ' . $faker->lastName,
                'name' => 'Building '. $i,
                'address' => 'Building '. $i . ' Full Address',
            ]);
            
            // Create 5 tasks for the current building
            for ($j=1; $j <= 5; $j++) {

                $userId = $faker->numberBetween(1, 10);

                $task = Task::create([
                    'title' => ucwords($faker->words(3, true)),
                    'description' => $faker->paragraph(2),
                    'status' => $faker->randomElement(['open', 'in_progress', 'completed', 'rejected']),
                    'user_id' => $userId,
                    'building_id' => $building->id,
                    'created_by' => $userId,
                    'created_at' => $faker->dateTimeBetween('-5 day'),
                ]);

                // Create 5 comments in the current task
                for ($k=1; $k <= 5; $k++) {
                    Comment::create([
                        'message' => $faker->sentence,
                        'user_id' => $faker->numberBetween(1, 10),
                        'task_id' => $task->id,
                    ]);
                }
            }
        }
    }
}
