<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['tip_id', 'event_type', 'p2p_event','global_event'];

    public function user(){
        return $this->belongsTo('App\Tip','tip_id');
    }

}

