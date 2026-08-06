<?php include "header.php"; ?>
<div class="container-fluid">
    <div class="row">
        <?php include "sidebar.php"; ?>
        <div class="col-md-10 content-area">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h2><?= isset($pageTitle) ? $pageTitle : "Trang quản trị" ?></h2>
                <div class="user-info">
                    <span class="me-2"><i class="fas fa-user-circle"></i> Admin</span>
                </div>
            </div>
            <?= $content ?>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
