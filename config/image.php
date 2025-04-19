<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    |
    | The driver that should be used by default.
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd', // Hoặc 'imagick' nếu bạn muốn sử dụng Imagick

    /*
    |--------------------------------------------------------------------------
    | Default Options
    |--------------------------------------------------------------------------
    |
    | The default options that will be applied to all images.
    |
    */

    'default' => [
        'width' => 0,
        'height' => 0,
        'quality' => 90,
    ],

];
