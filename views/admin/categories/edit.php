<?php
require_once "../../dao/CategoryDAO.php";

$categoryDAO = new CategoryDAO();
$errors = [];
$id = $_GET["id"] ?? 0;
$category = $categoryDAO->findById($id);

if (!$category) {
    die("Danh mục không tồn tại.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cateName = trim($_POST["cateName"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $status = $_POST["status"] ?? 1;

    // Validation
    if ($cateName == "") {
        $errors[] = "Tên danh mục không được để trống.";
    }
    if ($slug == "") {
        $errors[] = "Slug không được để trống.";
    }

    if (empty($errors)) {
        $category->catename = $cateName;
        $category->slug = $slug;
        $category->description = $description;
        $category->status = $status;
        
        if ($categoryDAO->update($category)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Cập nhật danh mục thất bại. Vui lòng thử lại.";
        }
    }
}

$pageTitle = "Cập nhật danh mục";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Cập nhật danh mục</h5>
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
            <input type="hidden" name="categoryId" value="<?= $category->id ?>">
            <div class="mb-3">
                <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="cateName" class="form-control" value="<?= htmlspecialchars($category->catename) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Slug <span class="text-danger">*</span></label>
                <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($category->slug) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" rows="5" class="form-control"><?= htmlspecialchars($category->description) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label d-block">Trạng thái</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?= $category->status == 1 ? "checked" : "" ?>>
                    <label class="form-check-label" for="status1">Hiển thị</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?= $category->status == 0 ? "checked" : "" ?>>
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
