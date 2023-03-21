<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_habit extends Model
{
    use HasFactory;

    public function habits()
    {
        return $this->belongsTo(Habit::class, 'fk_habit', 'id');
    }
}
