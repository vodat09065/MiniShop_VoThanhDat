<?php
require_once "../../dao/ProductDAO.php";
require_once "../../dao/CategoryDAO.php";
require_once "../../dao/BrandDAO.php";

$productDAO = new ProductDAO();
$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();

$categories = $categoryDAO->getAll();
$brands = $brandDAO->getAll();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = trim($_POST["productName"] ?? "");
    $slug = trim($_POST["slug"] ?? "");
    $categoryId = $_POST["categoryId"] ?? 0;
    $brandId = $_POST["brandId"] ?? 0;
    $price = $_POST["price"] ?? 0;
    $discountPrice = $_POST["discountPrice"] ?? 0;
    $quantity = $_POST["quantity"] ?? 0;
    $description = trim($_POST["description"] ?? "");
    $status = $_POST["status"] ?? 1;

    // Validation
    if ($productName == "") $errors[] = "Tên sản phẩm không được để trống.";
    if ($slug == "") $errors[] = "Slug không được để trống.";
    if ($categoryId == 0) $errors[] = "Vui lòng chọn danh mục.";
    if ($brandId == 0) $errors[] = "Vui lòng chọn thương hiệu.";
    if ($price <= 0) $errors[] = "Giá bán phải lớn hơn 0.";
    if ($quantity < 0) $errors[] = "Số lượng không hợp lệ.";

    if (empty($errors)) {
        // Mock image upload
        $image = null;

        $product = new Product($categoryId, $brandId, $productName, $slug, $price, $discountPrice, $quantity, $image, $description, $status);
        if ($productDAO->insert($product)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Thêm thất bại. Slug có thể bị trùng.";
        }
    }
}

$pageTitle = "Thêm sản phẩm";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Thêm mới sản phẩm</h5>
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

        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="productName" class="form-control" value="<?= isset($_POST['productName']) ? htmlspecialchars($_POST['productName']) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" name="slug" class="form-control" value="<?= isset($_POST['slug']) ? htmlspecialchars($_POST['slug']) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                    <select name="categoryId" class="form-select">
                        <option value="0">-- Chọn danh mục --</option>
                        <?php foreach($categories as $item): ?>
                            <option value="<?= $item->id ?>" <?= (isset($_POST['categoryId']) && $_POST['categoryId'] == $item->id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($item->catename) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Thương hiệu <span class="text-danger">*</span></label>
                    <select name="brandId" class="form-select">
                        <option value="0">-- Chọn thương hiệu --</option>
                        <?php foreach($brands as $item): ?>
                            <option value="<?= $item->id ?>" <?= (isset($_POST['brandId']) && $_POST['brandId'] == $item->id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($item->brandname) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" value="<?= isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '0' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Giá khuyến mãi</label>
                    <input type="number" name="discountPrice" class="form-control" value="<?= isset($_POST['discountPrice']) ? htmlspecialchars($_POST['discountPrice']) : '0' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="<?= isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '0' ?>">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="5" class="form-control"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?= (!isset($_POST['status']) || $_POST['status'] == 1) ? "checked" : "" ?>>
                        <label class="form-check-label" for="status1">Hiển thị</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?= (isset($_POST['status']) && $_POST['status'] == 0) ? "checked" : "" ?>>
                        <label class="form-check-label" for="status0">Ẩn</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
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
