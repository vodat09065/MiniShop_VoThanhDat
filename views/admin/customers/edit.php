<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MiniShop_VoThanhDat/autoload.php';



$customerDAO = new \DAO\CustomerDAO();
$errors = [];
$id = $_GET["id"] ?? 0;
$customer = $customerDAO->findById($id);

if (!$customer) {
    die("Khách hàng không tồn tại.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    \Middleware\CsrfMiddleware::verify();
    $fullname = trim($_POST["fullname"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $note = trim($_POST["note"] ?? "");

    if ($fullname == "") $errors[] = "Họ tên không được để trống.";
    if ($phone == "") $errors[] = "Số điện thoại không được để trống.";

    if (empty($errors)) {
        $customer->fullname = $fullname;
        $customer->phone = $phone;
        $customer->email = $email;
        $customer->address = $address;
        $customer->note = $note;

        if ($customerDAO->update($customer)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Cập nhật thất bại. Vui lòng thử lại.";
        }
    }
}

$pageTitle = "Cập nhật khách hàng";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Cập nhật khách hàng</h5>
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
            <div class="mb-3">
                <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($customer->fullname) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer->phone) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer->email ?? "") ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($customer->address ?? "") ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Ghi chú</label>
                <textarea name="note" rows="3" class="form-control"><?= htmlspecialchars($customer->note ?? "") ?></textarea>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                <button type="reset" class="btn btn-warning"><i class="fas fa-redo"></i> Làm mới</button>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
