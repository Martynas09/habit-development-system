<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_task extends Model
{
    use HasFactory;
    public function getTask()
    {
        return $this->belongsTo(Task::class, 'fk_task', 'id');
    }
    public function getPlan()
    {
        return $this->belongsTo(Plan::class, 'fk_plan', 'id');
    }
    public function getReminder()
    {
        return $this->hasOne(Reminder::class, 'fk_reminder', 'id');
    }
}
