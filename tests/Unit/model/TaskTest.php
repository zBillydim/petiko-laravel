<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create a task.
     */
    public function test_task_creation()
    {
        $user = User::factory()->create();
        $date = Carbon::now()->addDays(5)->format('d/m/Y');
        $task = Task::create([
            'user_id' => $user->id,
            'task_title' => 'Test Task',
            'task_description' => 'This is a test task description.',
            'due_date' => $date,
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->task_title);
        $this->assertEquals('This is a test task description.', $task->task_description);
        $this->assertEquals($date, $task->due_date);
        $this->assertEquals('pending', $task->status);
        $this->assertEquals('medium', $task->priority);
        $this->assertEquals($user->id, $task->user_id);
    }

    /**
     * Test due date casting.
     */
    public function test_due_date_cast()
    {
        $user = User::factory()->create();
        $date = Carbon::now()->addDays(5)->format('d/m/Y');
        $task = Task::create([
            'user_id' => $user->id,
            'task_title' => 'Test Task',
            'task_description' => 'This is a test task description.',
            'due_date' => $date,
            'status' => 'pending',
            'priority' => 'medium',
        ]);
        $this->assertEquals($date, $task->due_date);
        $this->assertIsString($task->due_date);
    }
    /**
     * Test relationship with user.
     */
    public function test_task_belongs_to_user()
    {
        $user = User::factory()->create();
        $date = Carbon::now()->addDays(5)->format('d/m/Y');
        $task = Task::create([
            'user_id' => $user->id,
            'task_title' => 'Test Task',
            'task_description' => 'This is a test task description.',
            'due_date' => $date,
            'status' => 'pending',
            'priority' => 'medium',
        ]);
        $this->assertInstanceOf(User::class, $task->user);
        $this->assertEquals($user->id, $task->user->id);
    }
}
