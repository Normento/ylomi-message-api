<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['sender_id', 'receiver_id'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}
