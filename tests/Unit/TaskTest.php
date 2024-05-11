<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Building;
use App\Models\Comment;

class TaskTest extends TestCase
{
    public function testCreateTask(): void
    {
        $building = Building::factory()->create();

        $task = Task::factory()->create([
            'building_id' => $building->id,
        ]);

        $comment = Comment::factory()->create([
            'task_id' => $task->id,
        ]);

        // Check if the task, building and comment were inserted into the database
        $this->assertDatabaseHas('tasks', $task->toArray());
        $this->assertDatabaseHas('buildings', $building->toArray());
        $this->assertDatabaseHas('comments', $comment->toArray());
    }

    public function testIfTaskCanChangeStatus(): void
    {
        $canChangeStatus = Task::factory()->create([
            'status' => 'open',
        ]);

        $this->assertIsBool($canChangeStatus);
        $this->assertTrue($canChangeStatus);
     
        $canChangeStatus = Task::factory()->create([
            'status' => 'completed',
        ]);

        $this->assertIsBool($canChangeStatus);
        $this->assertFalse($canChangeStatus);
    }
}
