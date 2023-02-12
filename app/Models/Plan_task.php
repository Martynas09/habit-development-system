<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_task extends Model
{
    use HasFactory;
    public function getTask()
    {
        return $this->hasMany(Task::class, 'id', 'fk_task');
    }
}
