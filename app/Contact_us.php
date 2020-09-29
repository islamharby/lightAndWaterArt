<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact_us extends Model
{
    protected $casts = [
        'is_action' => 'integer'
    ];
}
