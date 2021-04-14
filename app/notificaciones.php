<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class notificaciones extends Model
{
    
    protected $fillable = [
        "active",
        "email",
        "message"
    ];

}
