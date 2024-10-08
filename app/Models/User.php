<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'gender',
        'dob',
        'address',
        'email',
        'phone',
        'payload',
        'password',
        'profile_image',
        'otp',
    ];

    public function breeds(){
        return $this->hasMany(Breed::class);
    }
    public function records(){
        return $this->hasMany(Record::class);
    }
    public function sentrequest(){
        return $this->hasMany(Connection::class,'follower_id');
    }
    public function recievedrequest(){
        return $this->hasMany(Connection::class,'following_id');
    }
    public function farmersupply(){
        return $this->hasMany(Farmersupply::class);
    }
    public function milkmansupply(){
        return $this->hasMany(Milkmansupply::class);
    }
    public function withdrawsupplies(){
        return $this->hasMany(WithdrawSupply::class,'id');
    }
    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id');
    }

    /**
     * Get the transactions where the user is the receiver.
     */
    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'reciever_id');
    }
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'otp',
        'deleted_at',
        'expires_at',
        'created_at',
        'updated_at',
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
}
