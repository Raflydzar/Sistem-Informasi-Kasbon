<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Buat direktori sementara /tmp
$storagePath = '/tmp/storage';
$dirs = [
    $storagePath . '/app/public',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Load Autoload vendor & aplikasi Laravel
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// 3. Paksa Laravel menggunakan /tmp/storage
$app->useStoragePath($storagePath);

// 4. Jalankan penanganan request
$app->handleRequest(Request::capture());