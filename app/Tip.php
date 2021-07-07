<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    protected $fillable = [
        'recipient_id', 'sender_id','usd_equivalent', 'dash_amount', 'sent_by', 'message', 'status', 'praise', 'stamp','dash_usd'
    ];


    public function user(){
        return $this->belongsTo('App\User','recipient_id');
    }

    public function tip(){
        return $this->hasMany('App\Log','tip_id');
    }

}
