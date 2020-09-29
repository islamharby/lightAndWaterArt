<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintenanceQuotation extends Model
{
    protected $casts = [
        'is_action' => 'integer'
    ];
    public function product()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }
    public function service()
    {
        return $this->hasOne('App\Service', 'id', 'service_id');
    }
}
