<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
    public function image()
    {
        return $this->belongsTo('App\Image', 'image_id', 'id');
    }
}
