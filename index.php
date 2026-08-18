<?php
require_once __DIR__ . '/autoload.php';
session_start();

// Nhận Request
$area = $_GET["area"] ?? "client";
$controller = $_GET["controller"] ?? "home";
$action = $_GET["action"] ?? "index";

// Kiểm tra Authentication cho Admin
if ($area === "admin" && $controller !== "auth") {
    \Middleware\AuthMiddleware::handle();
}

// Kiểm tra Guest (Chỉ login)
if ($area === "admin" && $controller === "auth" && $action === "login") {
    \Middleware\GuestMiddleware::handle();
}

// Xác định tên Controller
$controllerClassName = ucfirst($controller) . "Controller";

if ($area === "admin") {
    $controllerClass = "Controllers\\Admin\\" . $controllerClassName;
} else {
    $controllerClass = "Controllers\\Client\\" . $controllerClassName;
}

// Kiểm tra Controller
if (!class_exists($controllerClass)) {
    die("Controller không tồn tại: $controllerClass");
}

// Tạo Controller
$controllerObject = new $controllerClass();

// Kiểm tra Action
if (!method_exists($controllerObject, $action)) {
    die("Action không tồn tại: $action");
}

// Gọi Action
$controllerObject->$action();
