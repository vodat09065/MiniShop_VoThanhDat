<?php
require_once __DIR__ . '/../autoload.php';

$dao = new \DAO\ProductDAO();
$products = $dao->getAll();

$dir = __DIR__ . '/../uploads/products/';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Download 5 reliable placeholders
$placeholderUrls = [
    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1527814050087-3793815479eb?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1583394838336-acd977736f90?q=80&w=800&auto=format&fit=crop',
];

$opts = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0\r\n"
    ]
];
$context = stream_context_create($opts);

$placeholders = [];
foreach ($placeholderUrls as $i => $url) {
    $filename = 'placeholder_' . $i . '.jpg';
    $filepath = $dir . $filename;
    if (!file_exists($filepath) || filesize($filepath) < 1000) {
        $data = @file_get_contents($url, false, $context);
        if ($data) {
            file_put_contents($filepath, $data);
            $placeholders[] = $filename;
        }
    } else {
        $placeholders[] = $filename;
    }
}

if (empty($placeholders)) {
    die("Failed to download any placeholders.\n");
}

$updated = 0;
foreach ($products as $product) {
    $existing = $dir . $product->image;
    // If no image or file doesn't exist
    if (empty($product->image) || !file_exists($existing) || !is_file($existing)) {
        $randomPlaceholder = $placeholders[array_rand($placeholders)];
        $product->image = $randomPlaceholder;
        $dao->update($product);
        $updated++;
        echo "Fixed product {$product->id} with {$randomPlaceholder}\n";
    }
}

echo "Done! Fixed {$updated} products.\n";
