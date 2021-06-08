<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelMessage extends Model
{
    
    use HasFactory;
    protected $table = 'messages';
    // protected $fillable = ['message'];

    public function user_messages(){
        return $this->hasMany(ModelUserMessage::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_messages', 'message_id', 'sender_id')
        ->withTimestamps();
    }
}
