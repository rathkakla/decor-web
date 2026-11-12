<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Aplikasi Android
    |--------------------------------------------------------------------------
    |
    | Berkas APK dideteksi otomatis dari storage/app/public/mobile/.
    | Cukup unggah "decor-v1.0.0.apk" maka halaman unduhan langsung memakainya.
    | Nilai di bawah hanya untuk menimpa (override) metadata opsional.
    |
    */

    'android' => [
        // Opsional: paksa URL tertentu. Kosongkan agar deteksi otomatis dipakai.
        'url' => env('ANDROID_APK_URL'),

        'min_os' => env('ANDROID_MIN_OS', 'Android 7.0'),

        'notes' => env('ANDROID_RELEASE_NOTES'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Aplikasi iOS
    |--------------------------------------------------------------------------
    |
    | Kosongkan selama aplikasi iOS belum tersedia. Halaman akan menampilkan
    | status "segera hadir" untuk pengunjung dari perangkat Apple.
    |
    */

    'ios' => [
        'url' => env('IOS_APP_URL'),
    ],

];
