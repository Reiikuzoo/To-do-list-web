<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'completed', 'deadline', 'priority', 'position'];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function subTasks()
    {
        return $this->hasMany(SubTask::class);
    }

    public function progress()
{
    $totalSubTasks = $this->subTasks()->count();
    $completedSubTasks = $this->subTasks()->where('completed', true)->count();

    return $totalSubTasks > 0 ? ($completedSubTasks / $totalSubTasks) * 100 : 0;
}

}
