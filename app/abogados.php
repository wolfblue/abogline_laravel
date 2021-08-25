<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class abogados extends Model
{
    protected $fillable = [
        "active",
        "fullname",
        "gender",
        "identification",
        "address",
        "document1",
        "university",
        "license",
        "experience",
        "years",
        "investigate",
        "pleasures",
        "pleasures_other",
        "price",
        "cv"
    ]; 
}
