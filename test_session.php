<?php
require_once __DIR__ . "/models/User.php";
session_start();
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
