<?php
require 'autoload.php';

$_POST['productid'] = 1;
$_SERVER['REQUEST_METHOD'] = 'POST';

$c = new \Controllers\Client\CartController();
$c->add();
