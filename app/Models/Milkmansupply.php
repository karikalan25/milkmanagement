<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milkmansupply extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable=[
        'reciever_id',
        'supply_id',
        'breed',
        'morning',
        'evening',
        'total',
        'price'
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user(){
        return $this->belongsTo(User::class,'reciever_id');
    }
}
