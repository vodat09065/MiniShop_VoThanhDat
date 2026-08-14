<?php
namespace Controllers\Admin;

use DAO\UserDAO;
use Middleware\CsrfMiddleware;
use Middleware\GuestMiddleware;

class AuthController
{
    public function login()
    {
        GuestMiddleware::handle();
        
        // Tạo token CSRF nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        CsrfMiddleware::generateToken();

        $errors = [];
        $username = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            CsrfMiddleware::verify();
            
            $username = trim($_POST["username"] ?? "");
            $password = $_POST["password"] ?? "";

            // validate
            if ($username === "") {
                $errors["username"] = "Vui lòng nhập tên đăng nhập.";
            }
            if ($password === "") {
                $errors["password"] = "Vui lòng nhập mật khẩu.";
            }

            // Nếu không có lỗi thì tìm user
            if (empty($errors)) {
                $userDAO = new UserDAO();
                $user = $userDAO->findByUsername($username);

                if (!$user) {
                    $errors["username"] = "Tên đăng nhập không tồn tại.";
                } elseif (!password_verify($password, $user->password)) {
                    $errors["password"] = "Mật khẩu không chính xác.";
                } else {
                    // Kiểm tra trạng thái tài khoản
                    if ($user->status != 1) {
                        $errors["username"] = "Tài khoản của bạn đã bị vô hiệu hóa.";
                    } else {
                        $_SESSION["user"] = $user;
                        // Chuyển đến Dashboard (Sẽ sửa route lại theo yêu cầu index.php)
                        header("Location: index.php?area=admin&controller=product&action=index");
                        exit;
                    }
                }
            }
        }

        // Gọi view (login form)
        require __DIR__ . "/../../views/admin/login.php";
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: index.php?area=admin&controller=auth&action=login");
        exit;
    }
}
