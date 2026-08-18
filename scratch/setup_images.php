<?php
require_once __DIR__ . '/../autoload.php';

$dao = new \DAO\ProductDAO();
$products = $dao->getAll();

$imageUrls = [
    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1527814050087-3793815479eb?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1583394838336-acd977736f90?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1603302576837-37561b2e2302?q=80&w=800&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?q=80&w=800&auto=format&fit=crop',
];

$dir = __DIR__ . '/../uploads/products/';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Download images context
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
    ]
];
$context = stream_context_create($opts);

foreach ($products as $index => $product) {
    $existing = $dir . $product->image;
    if (!empty($product->image) && file_exists($existing) && is_file($existing)) {
        continue;
    }
    
    $url = $imageUrls[$index % count($imageUrls)];
    echo "Downloading for {$product->proname}...\n";
    $imgData = @file_get_contents($url, false, $context);
    if ($imgData) {
        $filename = 'sample_' . $product->id . '.jpg';
        file_put_contents($dir . $filename, $imgData);
        $product->image = $filename;
        $dao->update($product);
        echo "Updated product {$product->id} with image {$filename}\n";
    } else {
        echo "Failed to download image for product {$product->id}\n";
    }
}
echo "Done!\n";
