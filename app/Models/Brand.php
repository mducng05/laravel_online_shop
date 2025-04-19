<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    // Chỉ định tên bảng tương ứng với model
    protected $table = 'brands'; // Đảm bảo tên bảng đúng với tên bảng trong cơ sở dữ liệu

    // Khai báo các thuộc tính có thể gán
    protected $fillable = ['name', 'slug', 'status']; // Danh sách các thuộc tính có thể gán
}