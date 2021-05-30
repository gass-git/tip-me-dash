<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitsRecord extends Model
{
    protected $table = 'visits_record';

    protected $fillable = [
        'page_owner_id','ip',
    ];
}
