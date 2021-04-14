<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    protected $fillable = [
        "active",
        "name",
        "lastname",
        "idType",
        "identification",
        "email2",
        "phone"
    ];
}
