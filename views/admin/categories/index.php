<?php
require_once "../../dao/CategoryDAO.php";

$categoryDAO = new CategoryDAO();
$keyword = "";
if (isset($_GET["keyword"])) {
    $keyword = trim($_GET["keyword"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnDelete"])) {
    $id = $_POST["id"] ?? 0;
    if ($categoryDAO->delete($id)) {
        header("Location: index.php?msg=deleted");
        exit();
    } else {
        $error = "Xóa thất bại!";
    }
}

$categories = $categoryDAO->getAll($keyword);
$pageTitle = "Quản lý danh mục";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Danh sách danh mục</h5>
        <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm mới</a>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Xóa danh mục thành công!</div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form class="row mb-3" method="GET">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Nhập từ khóa..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($categories) > 0): ?>
                        <?php foreach ($categories as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($item->catename) ?></td>
                            <td><?= htmlspecialchars($item->slug) ?></td>
                            <td>
                                <?php if ($item->status == 1): ?>
                                    <span class="badge bg-success">Hiển thị</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $item->createdAt ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="detail.php?id=<?= $item->id ?>" class="btn btn-info btn-sm text-white"><i class="fas fa-eye"></i> Chi tiết</a>
                                    <a href="edit.php?id=<?= $item->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Sửa</a>
                                    <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $item->id ?>">
                                        <button type="submit" name="btnDelete" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-danger">Không tìm thấy dữ liệu.</td>
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
