<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    use HasFactory;

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'fk_plan', 'id');
    }
    public function task()
    {
        return $this->belongsTo(Task::class, 'fk_task', 'id');
    }
    public function goal()
    {
        return $this->belongsTo(Goal::class, 'fk_goal', 'id');
    }
    public function habit()
    {
        return $this->belongsTo(Habit::class, 'fk_habit', 'id');
    }
}
