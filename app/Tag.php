<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }
    public function products()
    {
        return $this->belongsToMany('App\Product', 'products_tags', 'tag_id', 'product_id');
    }
}
