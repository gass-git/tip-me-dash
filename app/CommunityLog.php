<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunityLog extends Model
{
    protected $table = 'community_log';

    protected $fillable = [
        'from_user_id','to_user_id','is_msg','msg_id','is_boost',
    ];
}
