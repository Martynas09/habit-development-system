<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_character extends Model
{
    use HasFactory;

    public function getHead()
    {
        return $this->hasMany(Character_item::class, 'id', 'head');
    }
    public function getTop()
    {
        return $this->hasMany(Character_item::class, 'id', 'top');
    }
    public function getBottom()
    {
        return $this->hasMany(Character_item::class, 'id', 'bottom');
    }
    public function getShoes()
    {
        return $this->hasMany(Character_item::class, 'id', 'shoes');
    }
}
