<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawSupply extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_1_id',
        'user_2_id',
        'date',
        'withdraw',
        'description',
        'status'
    ];

    protected $hidden=[
        'created_at',
        'updated_at',
        'status'
    ];

    public function users(){
        return $this->belongsTo(User::class,'id');
    }
}
