<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;
    public function plan_goal()
    {
        return $this->hasMany(Plan_goal::class, 'fk_goal', 'id');
    }
}
