<?php
spl_autoload_register(function ($className) {
    $prefixes = [
        'Controllers\\' => __DIR__ . '/controllers/',
        'DAO\\'         => __DIR__ . '/dao/',
        'Models\\'      => __DIR__ . '/models/',
        'Middleware\\'  => __DIR__ . '/middleware/',
        'Config\\'      => __DIR__ . '/config/',
        'Composers\\'   => __DIR__ . '/composers/',
    ];
    
    foreach ($prefixes as $prefix => $baseDir) {
        // Kiểm tra class có thuộc namespace này không
        if (str_starts_with($className, $prefix)) {
            // Bỏ phần namespace gốc
            $relativeClass = substr($className, strlen($prefix));
            // Đổi dấu \ thành /
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            // Nếu file tồn tại thì nạp file
            if (file_exists($file)) {
                require_once $file;
            }
            return;
        }
    }
});

define('BASE_URL', '/MiniShop_VoThanhDat/');
define('PRODUCT_IMAGE_URL', BASE_URL . 'uploads/products/');
define('CART_SESSION_KEY', 'cart');
