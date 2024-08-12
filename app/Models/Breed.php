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
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function users(){
       return $this->belongsTo(User::class);
    }
    public function records(){
        return $this->hasMany(Record::class);
    }

}
