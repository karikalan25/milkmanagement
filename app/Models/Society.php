<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'timing',
        'incharge',
        'contact',
        'address',
        'breed',
        'price',
        'about',
        'photo'
    ];
    protected $hidden=[
        'created_at',
        'updated_at'
    ];
}
