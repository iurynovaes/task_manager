<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            $task = Task::create([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'status' => $faker->randomElement(['open', 'in_progress', 'completed', 'rejected']),
                'assigned_to' => $faker->name,
                'building_id' => $faker->numberBetween(1, 5), // Substitua pelo método adequado de seleção do edifício
            ]);

            // Criar alguns comentários para cada tarefa
            foreach (range(1, rand(1, 5)) as $index) {
                Comment::create([
                    'content' => $faker->sentence,
                    'task_id' => $task->id,
                ]);
            }
        }
    }
}
