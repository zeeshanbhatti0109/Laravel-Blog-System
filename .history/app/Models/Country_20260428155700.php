<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name'])]

class Country extends Model
{
    use HasFactory;
    
    public function user(){
        return $this->hasOne(User::class);
    }
}
