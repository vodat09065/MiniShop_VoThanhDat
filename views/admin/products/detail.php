<?php
require_once "../../dao/ProductDAO.php";

$productDAO = new ProductDAO();
$id = $_GET["id"] ?? 0;
$product = $productDAO->findById($id);

if (!$product) {
    die("Sản phẩm không tồn tại.");
}

$pageTitle = "Chi tiết sản phẩm";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Chi tiết sản phẩm: <?= htmlspecialchars($product->proname) ?></h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
                <tr><th width="200">ID</th><td><?= $product->id ?></td></tr>
                <tr><th>Tên sản phẩm</th><td><?= htmlspecialchars($product->proname) ?></td></tr>
                <tr><th>Danh mục</th><td><?= htmlspecialchars($product->cateName) ?></td></tr>
                <tr><th>Thương hiệu</th><td><?= htmlspecialchars($product->brandName) ?></td></tr>
                <tr><th>Slug</th><td><?= htmlspecialchars($product->slug) ?></td></tr>
                <tr><th>Giá bán</th><td class="text-danger fw-bold"><?= number_format($product->price, 0, ',', '.') ?> đ</td></tr>
                <tr><th>Giá khuyến mãi</th><td><?= number_format($product->discountPrice, 0, ',', '.') ?> đ</td></tr>
                <tr><th>Tồn kho</th><td><?= $product->quantity ?></td></tr>
                <tr><th>Mô tả</th><td><?= nl2br(htmlspecialchars($product->description ?? "")) ?></td></tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php if ($product->status == 1): ?>
                            <span class="badge bg-success">Hiển thị</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Ẩn</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr><th>Ngày tạo</th><td><?= $product->createdAt ?></td></tr>
                <tr><th>Ngày cập nhật</th><td><?= $product->updatedAt ?></td></tr>
            </tbody>
        </table>
        <div class="mt-4">
            <a href="edit.php?id=<?= $product->id ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Chỉnh sửa</a>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
