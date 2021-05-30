<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $fillable = [
        'likes_it','loves_it', 'brilliant','author_id','recipient_id'
    ];

    public function user(){
       return $this->belongsTo('App\User', 'recipient_id');
    }
}
