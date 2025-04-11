<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;

class DeleteExpiredTasks extends Command
{
    protected $signature = 'task:delete-expired';
    protected $description = 'Delete tasks that have passed their deadline';

    public function handle()
    {
        $deleted = Task::where('deadline', '<', Carbon::now())->delete();
        $this->info("Deleted $deleted expired tasks.");
    }
}
