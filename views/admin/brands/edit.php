<?php
require_once "../../dao/BrandDAO.php";

$brandDAO = new BrandDAO();
$errors = [];
$id = $_GET["id"] ?? 0;
$brand = $brandDAO->findById($id);

if (!$brand) {
    die("Thương hiệu không tồn tại.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brandName = trim($_POST["brandName"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = $_POST["status"] ?? 1;

    // Validation
    if ($brandName == "") {
        $errors[] = "Tên thương hiệu không được để trống.";
    }
    if ($slug == "") {
        $errors[] = "Slug không được để trống.";
    }

    if (empty($errors)) {
        $brand->brandname = $brandName;
        $brand->slug = $slug;
        $brand->description = $description;
        $brand->status = $status;
        
        if ($brandDAO->update($brand)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Cập nhật thương hiệu thất bại. Vui lòng thử lại.";
        }
    }
}

$pageTitle = "Cập nhật thương hiệu";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Cập nhật thương hiệu</h5>
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
            <input type="hidden" name="brandId" value="<?= $brand->id ?>">
            <div class="mb-3">
                <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                <input type="text" name="brandName" class="form-control" value="<?= htmlspecialchars($brand->brandname) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($brand->slug) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" rows="5" class="form-control"><?= htmlspecialchars($brand->description) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label d-block">Trạng thái</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?= $brand->status == 1 ? "checked" : "" ?>>
                    <label class="form-check-label" for="status1">Hiển thị</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?= $brand->status == 0 ? "checked" : "" ?>>
                    <label class="form-check-label" for="status0">Ẩn</label>
                </div>
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
