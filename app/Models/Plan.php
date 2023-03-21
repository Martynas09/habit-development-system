<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function getTasks()
    {
        return $this->hasMany(Plan_task::class, 'fk_plan', 'id');
    }
    public function getPrizes()
    {
        return $this->hasMany(Prize::class, 'fk_plan', 'id');
    }

}
