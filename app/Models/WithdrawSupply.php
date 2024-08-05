<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawSupply extends Model
{
    use HasFactory;

    protected $fillable=[
        'farmer_id',
        'milkman_id',
        'date',
        'withdraw',
        'description',
    ];

    protected $hidden=[
        'created_at',
        'updated_at'
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }
}
