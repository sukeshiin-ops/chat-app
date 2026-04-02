<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Message extends Model
{
    protected $fillable = [
        'message',
        'sender_id',
        'receiver_id'
    ];


    protected $table = 'chat_messages';


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
