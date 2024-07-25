<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TaskCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        
    }

    public function handle(TaskCreated $event)
    {
        \Log::info('Task Created: ', ['task' => $event->task]);
    }
}
