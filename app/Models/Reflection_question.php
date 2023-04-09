<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reflection_question extends Model
{
    public function answers()
    {
        return $this->hasMany(Reflection_answer::class, 'fk_question', 'id');
    }
}
