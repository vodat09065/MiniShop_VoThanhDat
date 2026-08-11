<?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin();
require_once "../../../dao/UserDAO.php";

$userDAO = new UserDAO();
$errors = [];
$id = $_GET["id"] ?? 0;
$user = $userDAO->findById($id);

if (!$user) {
    die("NgÆ°á»i dÃ¹ng khÃ´ng tá»“n táº¡i.");
}

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
    if ($phone == "") $errors[] = "Sá»‘ Ä‘iá»‡n thoáº¡i khÃ´ng Ä‘Æ°á»£c Ä‘á»ƒ trá»‘ng.";

    if (empty($errors)) {
        $user->fullname = $fullname;
        $user->username = $username;
        if (!empty($password)) { // Náº¿u nháº­p mk má»›i thÃ¬ update, khÃ´ng thÃ¬ giá»¯ nguyÃªn
            $user->password = $password;
        }
        $user->email = $email;
        $user->phone = $phone;
        $user->address = $address;
        $user->role = $role;
        $user->status = $status;

        if ($userDAO->update($user)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Cáº­p nháº­t tháº¥t báº¡i.";
        }
    }
}

$pageTitle = "Cáº­p nháº­t ngÆ°á»i dÃ¹ng";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Cáº­p nháº­t ngÆ°á»i dÃ¹ng</h5>
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
                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user->fullname) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user->username) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Máº­t kháº©u má»›i</label>
                    <input type="password" name="password" class="form-control" placeholder="Äá»ƒ trá»‘ng náº¿u khÃ´ng Ä‘á»•i">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sá»‘ Ä‘iá»‡n thoáº¡i <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user->phone) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user->email ?? "") ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vai trÃ²</label>
                    <select name="role" class="form-select">
                        <option value="0" <?= $user->role == 0 ? "selected" : "" ?>>NhÃ¢n viÃªn</option>
                        <option value="1" <?= $user->role == 1 ? "selected" : "" ?>>Quáº£n trá»‹</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Äá»‹a chá»‰</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user->address ?? "") ?>">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label d-block">Tráº¡ng thÃ¡i</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?= $user->status == 1 ? "checked" : "" ?>>
                        <label class="form-check-label" for="status1">Hoáº¡t Ä‘á»™ng</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?= $user->status == 0 ? "checked" : "" ?>>
                        <label class="form-check-label" for="status0">KhÃ³a</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cáº­p nháº­t</button>
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

