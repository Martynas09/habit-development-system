<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenged_user extends Model
{
    use HasFactory;

    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'fk_challenge', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'fk_user', 'id');
    }
}
