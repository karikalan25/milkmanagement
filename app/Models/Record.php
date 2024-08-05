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

    public function users(){
        return $this->belongsTo(User::class);
    }
    public function breeds(){
        return $this->belongsTo(Breed::class);
    }
}
