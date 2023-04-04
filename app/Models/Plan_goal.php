<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_goal extends Model
{
    use HasFactory;

    public function goals()
    {
        return $this->belongsTo(Goal::class, 'fk_goal', 'id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'fk_plan', 'id');
    }
}
