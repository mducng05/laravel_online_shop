<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    public function product_images() {
        return $this ->HasMany(ProductImage::class);
    }
    public function product_rating(){
        return $this->hasMany(ProductRating::class)->where('status',1);
    }
}