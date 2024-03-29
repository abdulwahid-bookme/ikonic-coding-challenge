<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sentRequests(){
        return $this->hasMany(Connection::class,'sender_id')->where('status','sent')->where('sender_id',auth()->user()->id);
    }
   
    public function receivedRequests(){
        return $this->hasMany(Connection::class,'receiver_id')->where('status','received')->where('receiver_id',auth()->user()->id);
    }

    public function receivedConnections(){
        return $this->hasMany(Connection::class,'receiver_id')->where('status','connected')->where('receiver_id',auth()->user()->id);
    }
    public function sentConnections(){
        return $this->hasMany(Connection::class,'sender_id')->where('status','connected')->where('sender_id',auth()->user()->id);
    }

    public function receivedCommonConnections(){
        return $this->hasMany(Connection::class,'receiver_id')->where('status','connected');
    }

    public function sentCommonConnections(){
        return $this->hasMany(Connection::class,'sender_id')->where('status','connected');
    }



}
