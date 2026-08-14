<?php
namespace Middleware;

class RoleMiddleware
{
    public static function checkAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user = $_SESSION["user"] ?? null;
        if (!$user) {
            header("Location: /MiniShop_VoThanhDat/views/admin/login.php");
            exit;
        }
        
        // Giả sử role = 1 là Admin, role = 0 là Nhân viên (Staff)
        if ($user->role != 1) {
            // Nếu không phải admin, chuyển hướng về trang báo lỗi quyền truy cập hoặc dashboard
            die("<h1 style='color:red;text-align:center;margin-top:50px;'>Bạn không có quyền truy cập vào chức năng này!</h1>
                 <p style='text-align:center;'><a href='/MiniShop_VoThanhDat/views/admin/dashboard.php'>Quay về Dashboard</a></p>");
        }
    }
}
