<?php
require_once "../../../dao/BrandDAO.php";

$brandDAO = new BrandDAO();
$id = $_GET["id"] ?? 0;
$brand = $brandDAO->findById($id);

if (!$brand) {
    die("Thương hiệu không tồn tại.");
}

$pageTitle = "Chi tiết thương hiệu";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Chi tiết thương hiệu: <?= htmlspecialchars($brand->brandname) ?></h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th width="200">ID</th>
                    <td><?= $brand->id ?></td>
                </tr>
                <tr>
                    <th>Tên thương hiệu</th>
                    <td><?= htmlspecialchars($brand->brandname) ?></td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td><?= htmlspecialchars($brand->slug) ?></td>
                </tr>
                <tr>
                    <th>Mô tả</th>
                    <td><?= nl2br(htmlspecialchars($brand->description)) ?></td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php if ($brand->status == 1): ?>
                            <span class="badge bg-success">Hiển thị</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Ẩn</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td><?= $brand->createdAt ?></td>
                </tr>
                <tr>
                    <th>Ngày cập nhật</th>
                    <td><?= $brand->updatedAt ?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="mt-4">
            <a href="edit.php?id=<?= $brand->id ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Chỉnh sửa</a>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
