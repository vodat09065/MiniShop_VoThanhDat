<?php
require_once "../../dao/ProductDAO.php";

$productDAO = new ProductDAO();
$keyword = "";
if (isset($_GET["keyword"])) {
    $keyword = trim($_GET["keyword"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnDelete"])) {
    $id = $_POST["id"] ?? 0;
    if ($productDAO->delete($id)) {
        header("Location: index.php?msg=deleted");
        exit();
    } else {
        $error = "Xóa thất bại! Sản phẩm có thể đang nằm trong đơn hàng.";
    }
}

$products = $productDAO->getAll($keyword);
$pageTitle = "Quản lý sản phẩm";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Danh sách sản phẩm</h5>
        <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm mới</a>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Xóa sản phẩm thành công!</div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form class="row mb-3" method="GET">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Tên sản phẩm, danh mục, thương hiệu..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $item): ?>
                        <tr>
                            <td><?= $item->id ?></td>
                            <td>
                                <?php if (!empty($item->image)): ?>
                                    <img src="../../uploads/products/<?= htmlspecialchars($item->image) ?>" width="50" height="50" style="object-fit:cover;">
                                <?php else: ?>
                                    <span class="text-muted">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item->proname) ?></td>
                            <td><?= htmlspecialchars($item->cateName) ?></td>
                            <td><?= htmlspecialchars($item->brandName) ?></td>
                            <td>
                                <div class="text-danger fw-bold"><?= number_format($item->price, 0, ',', '.') ?> đ</div>
                            </td>
                            <td><?= $item->quantity ?></td>
                            <td>
                                <?php if ($item->status == 1): ?>
                                    <span class="badge bg-success">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="detail.php?id=<?= $item->id ?>" class="btn btn-info btn-sm text-white"><i class="fas fa-eye"></i></a>
                                    <a href="edit.php?id=<?= $item->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $item->id ?>">
                                        <button type="submit" name="btnDelete" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-danger">Không tìm thấy dữ liệu.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
