<?php 
require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../middleware/CsrfMiddleware.php';
AuthMiddleware::handle();
CsrfMiddleware::generateToken();

$user = $_SESSION["user"] ?? null;
include "header.php"; 
?>
<div class="container-fluid">
    <div class="row">
        <?php include "sidebar.php"; ?>
        <div class="col-md-10 content-area">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h2><?= isset($pageTitle) ? $pageTitle : "Trang quản trị" ?></h2>
                
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle fs-3"></i>
                    <span>
                        <?= $user ? htmlspecialchars($user->fullname) : 'Khách' ?>
                    </span>
                    <a href="/Minishop_VoThanhDat/views/admin/logout.php" class="text-decoration-none text-danger ms-3">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </div>
            </div>
            <?= $content ?>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
