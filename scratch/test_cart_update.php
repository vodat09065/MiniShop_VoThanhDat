<?php
require 'autoload.php';

$_POST['productid'] = 45;
$_POST['quantity'] = 2;
$_SERVER['REQUEST_METHOD'] = 'POST';

session_start();
$_SESSION[CART_SESSION_KEY] = [
    45 => ["productid" => 45, "price" => 1000, "quantity" => 1]
];

$c = new \Controllers\Client\CartController();
$c->update();
