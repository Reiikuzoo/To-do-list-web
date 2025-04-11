<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    protected $fillable = ['title', 'task_id', 'completed' ,'position'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
