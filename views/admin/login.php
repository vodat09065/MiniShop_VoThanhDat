<?php
// Logic has been moved to AuthController
if (!isset($username) || !isset($errors)) {
    header("Location: /MiniShop_VoThanhDat/index.php?area=admin&controller=auth&action=login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5 mt-5">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Đăng nhập</h3>
                    <form action="/MiniShop_VoThanhDat/index.php?area=admin&controller=auth&action=login" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <div class="mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" name="username" class="form-control <?= isset($errors["username"]) ? 'is-invalid' : '' ?>"
                                   placeholder="Nhập tên đăng nhập" value="<?= htmlspecialchars($username) ?>" required>
                            <?php if (isset($errors["username"])): ?>
                                <div class="invalid-feedback">
                                    <?= $errors["username"] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control <?= isset($errors["password"]) ? 'is-invalid' : '' ?>"
                                   placeholder="Nhập mật khẩu" required>
                            <?php if (isset($errors["password"])): ?>
                                <div class="invalid-feedback">
                                    <?= $errors["password"] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember"> Ghi nhớ đăng nhập</label>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2">Đăng nhập</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
