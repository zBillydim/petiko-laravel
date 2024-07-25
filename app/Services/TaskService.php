<?php

namespace App\Services;

use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Events\TaskDeleted;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function createTask($validatedData)
    {
        $user = Auth::user();
        $validatedData['user_id'] = $user->id;
        $task = Task::create($validatedData);
        event(new TaskCreated($task));
        return $task;
    }

    public function updateTask(Task $task, $validatedData)
    {
        $user = Auth::user();
        $validatedData['user_id'] = $user->id;
        $task->update($validatedData);
        event(new TaskUpdated($task));
        return $task;
    }

    public function deleteTask(Task $task)
    {
        $task->delete();
        event(new TaskDeleted($task));
    }
}
