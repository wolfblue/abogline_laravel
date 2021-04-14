<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class procesos extends Model
{
    
    protected $fillable = [
        "active",
        "email",
        "id_caso"
    ];

}
