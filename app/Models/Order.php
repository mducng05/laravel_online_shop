<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    // Các cột được phép điền thông qua request
    protected $fillable = [
        'subtotal', 'shipping', 'grand_total', 'user_id',
        'first_name', 'last_name', 'email', 'moblie', 'address',
        'apartment', 'state', 'city', 'zip', 'notes', 'country_id'
    ];

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

}
