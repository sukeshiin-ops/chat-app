<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;

class Message extends Model
{
    use  SoftDeletes;
    protected $fillable = [
        'message',
        'sender_id',
        'receiver_id',
        'read_at',
        'delivered_at',
    
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
