<?php

namespace App\Listeners;

use App\Events\TaskDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TaskDeletedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(TaskDeleted $event)
    {
        \Log::info('Task Deleted: ', ['task' => $event->task]);
    }
}
