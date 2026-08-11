<?php
require_once "../../../dao/UserDAO.php";

$userDAO = new UserDAO();
$errors = [];
$id = $_GET["id"] ?? 0;
$user = $userDAO->findById($id);

if (!$user) {
    die("Người dùng không tồn tại.");
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

    if ($fullname == "") $errors[] = "Họ tên không được để trống.";
    if ($username == "") $errors[] = "Username không được để trống.";
    if ($phone == "") $errors[] = "Số điện thoại không được để trống.";

    if (empty($errors)) {
        $user->fullname = $fullname;
        $user->username = $username;
        if (!empty($password)) { // Nếu nhập mk mới thì update, không thì giữ nguyên
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
            $errors[] = "Cập nhật thất bại.";
        }
    }
}

$pageTitle = "Cập nhật người dùng";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Cập nhật người dùng</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= $err ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user->fullname) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user->username) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="password" class="form-control" placeholder="Để trống nếu không đổi">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user->phone) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user->email ?? "") ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Vai trò</label>
                    <select name="role" class="form-select">
                        <option value="0" <?= $user->role == 0 ? "selected" : "" ?>>Nhân viên</option>
                        <option value="1" <?= $user->role == 1 ? "selected" : "" ?>>Quản trị</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user->address ?? "") ?>">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?= $user->status == 1 ? "checked" : "" ?>>
                        <label class="form-check-label" for="status1">Hoạt động</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?= $user->status == 0 ? "checked" : "" ?>>
                        <label class="form-check-label" for="status0">Khóa</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
