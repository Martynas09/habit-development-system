<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_achievement extends Model
{
    use HasFactory;
    public function getUser()
    {
        return $this->belongsTo(User::class, 'fk_user', 'id');
    }
    public function getAchievement()
    {
        return $this->belongsTo(Achievement::class, 'fk_achievement', 'id');
    }
}
