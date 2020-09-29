<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }
    public function images()
    {
        return $this->belongsToMany('App\Image', 'event_images', 'event_id', 'image_id');
    }
}
