<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable=[
        'user_id',
        'reviewer_id',
        'ratings',
        'feedback'
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function reviewer(){
        return $this->belongsTo(User::class,'user_id');
    }
}
