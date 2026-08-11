<?php
require_once "../../../dao/ProductDAO.php";

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

$limit = (int)($_GET["limit"] ?? 10);
$page = (int)($_GET["page"] ?? 1);
$offset = ($page - 1) * $limit;
$sort = $_GET["sort"] ?? "";

$totalRecords = $productDAO->count("products", "proname", $keyword);
$totalPages = ceil($totalRecords / $limit);

$products = $productDAO->getPage($limit, $offset, $keyword, $sort);
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
                <input type="hidden" name="limit" value="<?= $limit ?>">
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex gap-4">
                <div class="d-flex align-items-center">
                    <label class="me-2 text-nowrap">Hiển thị:</label>
                    <form method="GET">
                        <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                        <select name="limit" class="form-select" onchange="this.form.submit()">
                            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                            <option value="30" <?= $limit == 30 ? 'selected' : '' ?>>30</option>
                        </select>
                    </form>
                </div>
                
                <div class="d-flex align-items-center">
                    <label class="me-2 text-nowrap">Sắp xếp:</label>
                    <form method="GET">
                        <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                        <input type="hidden" name="limit" value="<?= $limit ?>">
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="">Mặc định (Mới nhất)</option>
                            <option value="name_asc" <?= $sort == 'name_asc' ? 'selected' : '' ?>>Tên A-Z</option>
                            <option value="name_desc" <?= $sort == 'name_desc' ? 'selected' : '' ?>>Tên Z-A</option>
                            <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                            <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

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

        <?php if ($totalPages > 1): ?>
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&sort=<?= urlencode($sort) ?>&page=1">Đầu</a>
                </li>
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&sort=<?= urlencode($sort) ?>&page=<?= $page - 1 ?>">Trước</a>
                </li>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&sort=<?= urlencode($sort) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&sort=<?= urlencode($sort) ?>&page=<?= $page + 1 ?>">Sau</a>
                </li>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&sort=<?= urlencode($sort) ?>&page=<?= $totalPages ?>">Cuối</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
