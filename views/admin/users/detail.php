<?php
require_once "../../../dao/UserDAO.php";

$userDAO = new UserDAO();
$id = $_GET["id"] ?? 0;
$user = $userDAO->findById($id);

if (!$user) {
    die("Người dùng không tồn tại.");
}

$pageTitle = "Chi tiết người dùng";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Chi tiết người dùng: <?= htmlspecialchars($user->fullname) ?></h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr><th width="200">ID</th><td><?= $user->id ?></td></tr>
                <tr><th>Họ tên</th><td><?= htmlspecialchars($user->fullname) ?></td></tr>
                <tr><th>Username</th><td><?= htmlspecialchars($user->username) ?></td></tr>
                <tr><th>Số điện thoại</th><td><?= htmlspecialchars($user->phone) ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($user->email ?? "") ?></td></tr>
                <tr><th>Địa chỉ</th><td><?= htmlspecialchars($user->address ?? "") ?></td></tr>
                <tr>
                    <th>Vai trò</th>
                    <td>
                        <?php if ($user->role == 1): ?>
                            <span class="badge bg-danger">Quản trị</span>
                        <?php else: ?>
                            <span class="badge bg-info text-dark">Nhân viên</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php if ($user->status == 1): ?>
                            <span class="badge bg-success">Hoạt động</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Khóa</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr><th>Ngày tạo</th><td><?= $user->createdAt ?></td></tr>
                <tr><th>Ngày cập nhật</th><td><?= $user->updatedAt ?></td></tr>
            </tbody>
        </table>
        <div class="mt-4">
            <a href="edit.php?id=<?= $user->id ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Chỉnh sửa</a>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
