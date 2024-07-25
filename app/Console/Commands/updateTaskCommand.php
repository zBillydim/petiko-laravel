<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Events\TaskOverdue;

class updateTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update task due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::where('deleted_at', null)
                    ->where('due_date', '<', now())
                    ->whereNotIn('status', ['completed','overdue'])
                    ->get();           
        foreach ($tasks as $task) {
            $task->update(['status' => 'overdue']);
            event(new TaskOverdue($task));
        }
        $this->info('Task due dates updated and notifications sent successfully');
    }
}
