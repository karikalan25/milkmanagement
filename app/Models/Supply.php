<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'farmer_id',
        'milkman_id',
        'breed_id',
        'morning',
        'evening',
        'total',
        'price',
    ];

    protected $hidden=[
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }
    public function breeds(){
        return $this->belongsTo(Breed::class);
    }
}
