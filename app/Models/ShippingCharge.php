<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;

    // Định nghĩa mối quan hệ với mô hình Country
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    // Các phương thức và thuộc tính khác có thể có
}