<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestQuotation extends Model
{
    protected $casts = [
        'is_action' => 'integer'
    ];
    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }
}
