<?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin();
require_once "../../../dao/UserDAO.php";

$userDAO = new UserDAO();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    CsrfMiddleware::verify();
    $fullname = trim($_POST["fullname"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $role = $_POST["role"] ?? 0;
    $status = $_POST["status"] ?? 1;

    if ($fullname == "") $errors[] = "Há» tÃªn khÃ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng.";
    if ($username == "") $errors[] = "Username khÃ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng.";
    if ($password == "") $errors[] = "Máº­t kháº©u khÃ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng.";
    if ($phone == "") $errors[] = "Sá»‘ Ä‘iá»‡n thoáº¡i khÃ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng.";

    if (empty($errors)) {
        // Trong thá»±c táº¿, cáº§n mÃ£ hÃ³a password, vÃ­ dá»¥: password_hash($password, PASSWORD_DEFAULT)
        // Tuy nhiÃªn lab khÃ´ng yÃªu cáº§u chi tiáº¿t nÃªn mÃ¬nh dÃ¹ng nguyÃªn báº£n hoáº·c md5 tÃ¹y Ã½.
        $user = new User($fullname, $username, $password, $email, $phone, $address, $role, $status);
        if ($userDAO->insert($user)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "ThÃªm tháº¥t báº¡i. Username cÃ³ thá»ƒ Ä‘Ã£ tá»“n táº¡i.";
        }
    }
}

$pageTitle = "ThÃªm ngÆ°á»i dÃ¹ng";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">ThÃªm má»›i ngÆ°á»i dÃ¹ng</h5>
    </div>
    <div class="card-body">
        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); foreach ($errors as $err): ?>
                        <li><?= $err ?></li>
                    <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endforeach; ?>
                </ul>
            </div>
        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endif; ?>

        <form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Há» tÃªn <span class="text-danger">*</span></label>
                    <input type="text" name="fullname" class="form-control" value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Máº­t kháº©u <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sá»‘ Ä‘iá»‡n thoáº¡i <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vai trÃ²</label>
                    <select name="role" class="form-select">
                        <option value="0" <?= (isset($_POST['role']) && $_POST['role'] == 0) ? "selected" : "" ?>>NhÃ¢n viÃªn</option>
                        <option value="1" <?= (isset($_POST['role']) && $_POST['role'] == 1) ? "selected" : "" ?>>Quáº£n trá»‹</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Äá»‹a chá»‰</label>
                    <input type="text" name="address" class="form-control" value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label d-block">Tráº¡ng thÃ¡i</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?= (!isset($_POST['status']) || $_POST['status'] == 1) ? "checked" : "" ?>>
                        <label class="form-check-label" for="status1">Hoáº¡t Ä‘á»™ng</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?= (isset($_POST['status']) && $_POST['status'] == 0) ? "checked" : "" ?>>
                        <label class="form-check-label" for="status0">KhÃ³a</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> LÆ°u</button>
                <button type="reset" class="btn btn-warning"><i class="fas fa-redo"></i> LÃ m má»›i</button>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay láº¡i</a>
            </div>
        </form>
    </div>
</div>

<?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin();
$content = ob_get_clean();
include "../layouts/master.php";
?>

