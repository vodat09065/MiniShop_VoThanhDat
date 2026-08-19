<?php
require_once 'autoload.php';
$dao = new \DAO\ProductDAO();
$products = $dao->getAll();
echo "Total products: " . count($products) . "\n";
foreach(array_slice($products, 0, 5) as $p) {
    echo "ID: " . $p->id . " Image: '" . $p->image . "'\n";
}
