<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'farmer_id',
        'milkman_id',
        'amount',
        'status',
        'payout',
        'scheduled_for'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function breed(){
        return $this->belongsTo(Breed::class);
    }
}
