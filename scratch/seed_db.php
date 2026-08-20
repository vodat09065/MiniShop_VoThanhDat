<?php
require_once __DIR__ . '/../autoload.php';

$catDao = new \DAO\CategoryDAO();
$prodDao = new \DAO\ProductDAO();

// 1. Setup specific categories
$targetCategories = [
    'TV' => 'tv',
    'Laptop' => 'laptop',
    'Điện thoại' => 'dien-thoai',
    'Phụ kiện' => 'phu-kien'
];

$existingCats = $catDao->getAll();
$catMap = []; // slug => id

// Create or update categories
foreach ($targetCategories as $name => $slug) {
    $found = false;
    foreach ($existingCats as $c) {
        if ($c->slug === $slug || $c->catename === $name) {
            $c->catename = $name;
            $c->slug = $slug;
            $catDao->update($c);
            $catMap[$slug] = $c->id;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $newCat = new \Models\Category($name, $slug, 1);
        $catDao->insert($newCat);
    }
}

$existingCats = $catDao->getAll();
foreach ($existingCats as $c) {
    $catMap[$c->slug] = $c->id;
}

// 2. Generate realistic products
$techProducts = [
    'tv' => [
        ['name' => 'Smart TV Samsung 4K 65 inch', 'price' => 15000000, 'discount' => 14000000],
        ['name' => 'Sony Bravia OLED 55 inch', 'price' => 25000000, 'discount' => 23500000],
        ['name' => 'LG 4K UHD 50 inch', 'price' => 12000000, 'discount' => 0],
    ],
    'laptop' => [
        ['name' => 'MacBook Pro 14 M2', 'price' => 45000000, 'discount' => 43000000],
        ['name' => 'Dell XPS 13 Plus', 'price' => 38000000, 'discount' => 35000000],
        ['name' => 'ASUS ROG Zephyrus G14', 'price' => 32000000, 'discount' => 0],
        ['name' => 'Lenovo ThinkPad X1 Carbon', 'price' => 40000000, 'discount' => 38000000],
    ],
    'dien-thoai' => [
        ['name' => 'iPhone 15 Pro Max 256GB', 'price' => 34000000, 'discount' => 32900000],
        ['name' => 'Samsung Galaxy S24 Ultra', 'price' => 33000000, 'discount' => 31000000],
        ['name' => 'Xiaomi 14 Pro', 'price' => 22000000, 'discount' => 0],
        ['name' => 'Oppo Find X7 Ultra', 'price' => 25000000, 'discount' => 24000000],
    ],
    'phu-kien' => [
        ['name' => 'Tai nghe AirPods Pro 2', 'price' => 6000000, 'discount' => 5500000],
        ['name' => 'Chuột Logitech MX Master 3S', 'price' => 2500000, 'discount' => 0],
        ['name' => 'Bàn phím cơ Keychron K8 Pro', 'price' => 2200000, 'discount' => 2000000],
        ['name' => 'Cáp sạc Anker Type-C 100W', 'price' => 500000, 'discount' => 0],
    ]
];

function makeSlug($str) {
    $str = mb_strtolower($str, 'UTF-8');
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/[^a-z0-9\-]/', '-', $str);
    $str = preg_replace('/-+/', '-', $str);
    return trim($str, '-');
}

$allProducts = $prodDao->getAll();
$productIndex = 0;

foreach ($techProducts as $catSlug => $items) {
    if (!isset($catMap[$catSlug])) continue;
    $categoryId = $catMap[$catSlug];
    
    foreach ($items as $item) {
        if ($productIndex < count($allProducts)) {
            $p = $allProducts[$productIndex];
            $p->proname = $item['name'];
            $p->slug = makeSlug($item['name']) . '-' . rand(100, 999);
            $p->categoryId = $categoryId;
            $p->price = $item['price'];
            $p->discountPrice = $item['discount'];
            // Keep existing image if it is placeholder
            if (empty($p->image) || strpos($p->image, 'placeholder_') === false) {
                $p->image = 'placeholder_' . rand(0, 4) . '.jpg';
            }
            $prodDao->update($p);
        } else {
            // We ran out of existing products, create a new one
            // brandId = 1 (default)
            $p = new \Models\Product(
                $categoryId, 1, $item['name'], makeSlug($item['name']) . '-' . rand(100, 999),
                $item['price'], $item['discount'], 100, 'placeholder_'.rand(0,4).'.jpg', 'Mô tả ' . $item['name'], 1
            );
            $prodDao->insert($p);
        }
        $productIndex++;
    }
}

// Any remaining old products can be hidden or deleted, but let's just update their categories to something valid
while ($productIndex < count($allProducts)) {
    $p = $allProducts[$productIndex];
    // Assign to a random category
    $p->categoryId = $catMap[array_rand($catMap)];
    // Make their names realistic as well
    $p->proname = "Sản phẩm công nghệ " . rand(1000, 9999);
    $p->slug = makeSlug($p->proname);
    $prodDao->update($p);
    $productIndex++;
}

echo "Database seeded successfully!";
