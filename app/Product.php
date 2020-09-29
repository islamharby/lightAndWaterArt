<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }
    public function images()
    {
        return $this->belongsToMany('App\Image', 'product_images', 'product_id', 'image_id');
    }
}
