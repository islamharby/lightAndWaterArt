<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }
}
