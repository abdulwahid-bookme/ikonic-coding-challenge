<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id','receiver_id','status'];

    public function receiverDetails(){
        return $this->hasOne(User::class,'id','receiver_id');
    }
    public function senderDetails(){
        return $this->hasOne(User::class,'id','sender_id');
    }

    public function connectedReceiverDetails(){
        return $this->hasOne(User::class,'id','receiver_id');
    }

    
}
