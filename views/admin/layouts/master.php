<?php 
use Middleware\AuthMiddleware;
use Middleware\CsrfMiddleware;

\Middleware\AuthMiddleware::handle();
\Middleware\CsrfMiddleware::generateToken();

$user = $_SESSION["user"] ?? null;
include __DIR__ . "/header.php"; 
?>
<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . "/sidebar.php"; ?>
        <div class="col-md-10 content-area">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h2><?= isset($pageTitle) ? $pageTitle : "Trang quản trị" ?></h2>
                
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle fs-3"></i>
                    <span>
                        <?= $user ? htmlspecialchars($user->fullname) : 'Khách' ?>
                    </span>
                    <a href="/MiniShop_VoThanhDat/index.php?area=admin&controller=auth&action=logout" class="text-decoration-none text-danger ms-3">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </div>
            </div>
            <?= $content ?>
        </div>
    </div>
</div>
<?php include __DIR__ . "/footer.php"; ?>
