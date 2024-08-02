<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'supply',
        'litres',
        'maximum_price',
        'minimum_price',
    ];

    public function users(){
       return $this->belongsTo(User::class);
    }
}
