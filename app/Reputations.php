<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reputations extends Model
{
    protected $fillable = [
        'clicker_id','recipient_id', 'status',
    ];


    public function user(){
        return $this->belongsTo('App\User','recipient_id');
    }
}
