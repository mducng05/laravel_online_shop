<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                        $countries  = array(
            array('code' => 'HNI', 'name' => 'Hà Nội'),
            array('code' => 'HCM', 'name' => 'TP. Hồ Chí Minh'),
            array('code' => 'DNG', 'name' => 'Đà Nẵng'),
            array('code' => 'BD', 'name' => 'Bình Dương'),
            array('code' => 'DN', 'name' => 'Đồng Nai'),
            array('code' => 'HP', 'name' => 'Hải Phòng'),
            array('code' => 'AG', 'name' => 'An Giang'),
            array('code' => 'BRVT', 'name' => 'Bà Rịa - Vũng Tàu'),
            array('code' => 'BL', 'name' => 'Bạc Liêu'),
            array('code' => 'BG', 'name' => 'Bắc Giang'),
            array('code' => 'BK', 'name' => 'Bắc Kạn'),
            array('code' => 'BN', 'name' => 'Bắc Ninh'),
            array('code' => 'BT', 'name' => 'Bến Tre'),
            array('code' => 'BDI', 'name' => 'Bình Định'),
            array('code' => 'BP', 'name' => 'Bình Phước'),
            array('code' => 'BTU', 'name' => 'Bình Thuận'),
            array('code' => 'CM', 'name' => 'Cà Mau'),
            array('code' => 'CB', 'name' => 'Cao Bằng'),
            array('code' => 'CT', 'name' => 'Cần Thơ'),
            array('code' => 'GL', 'name' => 'Gia Lai'),
            array('code' => 'HG', 'name' => 'Hà Giang'),
            array('code' => 'HT', 'name' => 'Hà Tĩnh'),
            array('code' => 'HD', 'name' => 'Hải Dương'),
            array('code' => 'HB', 'name' => 'Hòa Bình'),
            array('code' => 'HY', 'name' => 'Hưng Yên'),
            array('code' => 'KH', 'name' => 'Khánh Hòa'),
            array('code' => 'KG', 'name' => 'Kiên Giang'),
            array('code' => 'KT', 'name' => 'Kon Tum'),
            array('code' => 'LC', 'name' => 'Lai Châu'),
            array('code' => 'LD', 'name' => 'Lâm Đồng'),
            array('code' => 'LS', 'name' => 'Lạng Sơn'),
            array('code' => 'LA', 'name' => 'Long An'),
            array('code' => 'ND', 'name' => 'Nam Định'),
            array('code' => 'NA', 'name' => 'Nghệ An'),
            array('code' => 'NB', 'name' => 'Ninh Bình'),
            array('code' => 'NT', 'name' => 'Ninh Thuận'),
            array('code' => 'PT', 'name' => 'Phú Thọ'),
            array('code' => 'PY', 'name' => 'Phú Yên'),
            array('code' => 'QB', 'name' => 'Quảng Bình'),
            array('code' => 'QNA', 'name' => 'Quảng Nam'),
            array('code' => 'QNG', 'name' => 'Quảng Ngãi'),
            array('code' => 'QN', 'name' => 'Quảng Ninh'),
            array('code' => 'QT', 'name' => 'Quảng Trị'),
            array('code' => 'ST', 'name' => 'Sóc Trăng'),
            array('code' => 'SL', 'name' => 'Sơn La'),
            array('code' => 'TN', 'name' => 'Tây Ninh'),
            array('code' => 'TB', 'name' => 'Thái Bình'),
            array('code' => 'TNN', 'name' => 'Thái Nguyên'),
            array('code' => 'TH', 'name' => 'Thanh Hóa'),
            array('code' => 'TTH', 'name' => 'Thừa Thiên Huế'),
            array('code' => 'TG', 'name' => 'Tiền Giang'),
            array('code' => 'TV', 'name' => 'Trà Vinh'),
            array('code' => 'TQ', 'name' => 'Tuyên Quang'),
            array('code' => 'VL', 'name' => 'Vĩnh Long'),
            array('code' => 'VP', 'name' => 'Vĩnh Phúc'),
            array('code' => 'YB', 'name' => 'Yên Bái'),
            array('code' => 'DB', 'name' => 'Điện Biên'),
            array('code' => 'HNA', 'name' => 'Hà Nam'),
            array('code' => 'ROW', 'name' => 'Rest of World')
        );

        DB::table('countries')->insert($countries);


    }
}
