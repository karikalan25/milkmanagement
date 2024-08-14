<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable=[
        'sender_id',
        'reciever_id',
        'paid_amount',
        'recieved_amount',
        'balance_amount',
        'payment_method',
        'cash',
        'upi',
        'proof',
        'status'
    ];

    protected $hidden=[
        'created_at',
        'updated_at'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the user who received the transaction.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'reciever_id');
    }
}
