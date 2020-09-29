<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }
    public function tags()
    {
        return $this->hasMany('App\Tag', 'category_id', 'id');
    }
    public function products()
    {
        return $this->hasMany('App\Product', 'category_id', 'id');
    }
}
