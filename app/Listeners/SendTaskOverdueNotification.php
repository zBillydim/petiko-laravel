<?php

namespace App\Listeners;

use App\Events\TaskOverdue;
use App\Mail\TaskOverdueNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendTaskOverdueNotification implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(TaskOverdue $event)
    {
        $task = $event->task;
        $user = $task->user;
        Mail::to($user->email)->send(new TaskOverdueNotification($task));
    }
}
