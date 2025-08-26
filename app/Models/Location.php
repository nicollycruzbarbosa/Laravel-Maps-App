<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
        protected $fillable = ['name','description','latitude','longitude','address','category'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

}


