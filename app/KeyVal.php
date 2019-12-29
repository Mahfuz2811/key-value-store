<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeyVal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'key', 'value', 'last_store_time'
    ];
}
