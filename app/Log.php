<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';

    protected $fillable = [
        'to_user_id','from_user_id','log','rep_change',
    ];

    public function user(){
        return $this->belongsTo('App\User','to_user_id');
    }
}
