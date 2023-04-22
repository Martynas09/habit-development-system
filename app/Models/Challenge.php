<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    public function challenged_users()
    {
        return $this->hasMany(Challenged_user::class,'fk_challenge','id');
    }
    public function challenge_author()
    {
        return $this->belongsTo(User::class,'fk_user','id');
    }
}
