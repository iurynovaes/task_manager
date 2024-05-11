<?php

namespace Tests\Unit;

use App\Enums\TaskStatus;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function testValidateTaskStatus(): void
    {
        // Create a task with a valid status
        $taskValidStatus = TaskStatus::isValid('open');
        $this->assertTrue($taskValidStatus);

        // Create a task with an invalid status
        $taskInvalidStatus = TaskStatus::isValid('invalid_status');
        $this->assertFalse($taskInvalidStatus);
    }
    
    public function testGettingValidTaskStatuses(): void
    {
        // Create a task with a valid status
        $list = TaskStatus::getValues();
        $this->assertIsArray($list);
        $this->assertNotEmpty($list);
    }
}
