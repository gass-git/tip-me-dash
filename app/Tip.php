<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    protected $fillable = [
        'recipient_id', 'recipient_email', 'sender_id', 'sender_ip', 'sender_email', 'usd_equivalent', 'dash_amount', 
        'sent_by', 'message', 'status', 'praise', 'stamp','dash_usd','private_msg'
    ];


    public function user(){
        return $this->belongsTo('App\User','recipient_id');
    }

    public function tip(){
        return $this->hasMany('App\Log','tip_id');
    }

}
