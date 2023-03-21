<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    use HasFactory;

    public function plan_habits()
    {
        return $this->hasMany(Plan_habit::class, 'fk_habit', 'id');
    }
}
