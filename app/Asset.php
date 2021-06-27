<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Asset extends Model
{
    protected $fillable = [
        'type',
        'serial_number',
        'description',
        'fixed_movable',
        'picture_path',
        'purchase_date',
        'start_use_date',
        'purchase_price',
        'warranty_expiry_date',
        'degredation_in_years',
        'current_value_in_naira',
        'location',
    ];

    public function setPurchaseDateAttribute($value)
    {
        return $this->attributes['purchase_date'] = Carbon::parse($value);
    }

    public function setStartUseDateAttribute($value)
    {
        return $this->attributes['start_use_date'] = Carbon::parse($value);
    }

    public function setWarrantyExpiryDateAttribute($value)
    {
        return $this->attributes['warranty_expiry_date'] = Carbon::parse($value);
    }
}
