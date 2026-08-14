<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/MiniShop_VoThanhDat/autoload.php';



$categoryDAO = new \DAO\CategoryDAO();
$id = $_GET["id"] ?? 0;
$category = $categoryDAO->findById($id);

if (!$category) {
    die("Danh mục không tồn tại.");
}

$pageTitle = "Chi tiết danh mục";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Chi tiết danh mục: <?= htmlspecialchars($category->catename) ?></h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th width="200">ID</th>
                    <td><?= $category->id ?></td>
                </tr>
                <tr>
                    <th>Tên danh mục</th>
                    <td><?= htmlspecialchars($category->catename) ?></td>
                </tr>
                <tr>
                    <th>Slug</th>
                    <td><?= htmlspecialchars($category->slug) ?></td>
                </tr>
                <tr>
                    <th>Hình ảnh</th>
                    <td>
                        <?php if (!empty($category->image)): ?>
                            <img src="/MiniShop_VoThanhDat/uploads/categories/<?= htmlspecialchars($category->image) ?>" class="img-thumbnail" width="150">
                        <?php else: ?>
                            <span class="text-muted">No Image</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Mô tả</th>
                    <td><?= nl2br(htmlspecialchars($category->description)) ?></td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php if ($category->status == 1): ?>
                            <span class="badge bg-success">Hiển thị</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Ẩn</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td><?= $category->createdAt ?></td>
                </tr>
                <tr>
                    <th>Ngày cập nhật</th>
                    <td><?= $category->updatedAt ?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="mt-4">
            <a href="edit.php?id=<?= $category->id ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Chỉnh sửa</a>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
