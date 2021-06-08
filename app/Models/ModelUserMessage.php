<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelUserMessage extends Model
{
    use HasFactory;

    // protected $table = 'user_messages';
    public function message(){
        return $this->belongsTo(ModelMessage::class);
    }
}
