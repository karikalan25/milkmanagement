<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'breed_id',
        'morning',
        'evening',
        'price',
        'notes',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class,'id');
    }
    public function breed(){
        return $this->belongsTo(Breed::class,'id');
    }
}
