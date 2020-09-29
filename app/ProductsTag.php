<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsTag extends Model
{
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
    public function tag()
    {
        return $this->belongsTo('App\Tag', 'tag_id', 'id');
    }
}
