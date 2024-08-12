<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farmersupply extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable=[
        'supply_id',
        'reciever_id',
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
        return $this->belongsTo(User::class,'supply_id');
    }
}
