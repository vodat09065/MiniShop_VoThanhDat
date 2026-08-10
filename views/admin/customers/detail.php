<?php
require_once "../../dao/CustomerDAO.php";

$customerDAO = new CustomerDAO();
$id = $_GET["id"] ?? 0;
$customer = $customerDAO->findById($id);

if (!$customer) {
    die("Khách hàng không tồn tại.");
}

$pageTitle = "Chi tiết khách hàng";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Chi tiết khách hàng: <?= htmlspecialchars($customer->fullname) ?></h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr><th width="200">ID</th><td><?= $customer->id ?></td></tr>
                <tr><th>Họ tên</th><td><?= htmlspecialchars($customer->fullname) ?></td></tr>
                <tr><th>Số điện thoại</th><td><?= htmlspecialchars($customer->phone) ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($customer->email ?? "") ?></td></tr>
                <tr><th>Địa chỉ</th><td><?= htmlspecialchars($customer->address ?? "") ?></td></tr>
                <tr><th>Ghi chú</th><td><?= nl2br(htmlspecialchars($customer->note ?? "")) ?></td></tr>
            </tbody>
        </table>
        <div class="mt-4">
            <a href="edit.php?id=<?= $customer->id ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Chỉnh sửa</a>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
