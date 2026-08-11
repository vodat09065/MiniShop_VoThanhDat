<?php
require_once "../../../dao/ProductDAO.php";
require_once "../../../dao/CategoryDAO.php";
require_once "../../../dao/BrandDAO.php";

$productDAO = new ProductDAO();
$categoryDAO = new CategoryDAO();
$brandDAO = new BrandDAO();

$id = $_GET["id"] ?? 0;
$product = $productDAO->findById($id);

if (!$product) {
    die("Sản phẩm không tồn tại.");
}

$categories = $categoryDAO->getAll();
$brands = $brandDAO->getAll();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    CsrfMiddleware::verify();
    if (isset($_POST["btnDeleteImage"])) {
        $imgId = (int)$_POST["deleteImageId"];
        $productDAO->deleteImage($imgId);
        header("Location: edit.php?id=$id");
        exit();
    }

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
        $imageName = $product->image;
        $fileName = $_FILES["image"]["name"] ?? "";
        if ($fileName != "") {
            $tmpName = $_FILES["image"]["tmp_name"] ?? "";
            $fileSize = $_FILES["image"]["size"] ?? 0;
            $error = $_FILES["image"]["error"] ?? 0;
            
            if ($error != UPLOAD_ERR_OK) {
                $errors[] = "Upload hình ảnh không thành công.";
            }
            
            $allowExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowExtensions)) {
                $errors[] = "Chỉ cho phép file JPG, JPEG, PNG, GIF hoặc WEBP.";
            }
            
            $maxSize = 200 * 1024;
            if ($fileSize > $maxSize) {
                $errors[] = "Kích thước hình ảnh <= 200 KB.";
            }
            
            if (empty($errors)) {
                $imageName = time() . "_" . $slug . "." . $extension;
                $uploadPath = __DIR__ . "/../../../uploads/products/" . $imageName;
                if (!empty($product->image)) {
                    $oldImage = __DIR__ . "/../../../uploads/products/" . $product->image;
                    if (file_exists($oldImage) && is_file($oldImage)) {
                        unlink($oldImage);
                    }
                }
                move_uploaded_file($tmpName, $uploadPath);
            }
        }

        if (empty($errors)) {
        $product->proname = $productName;
        $product->slug = $slug;
        $product->categoryId = $categoryId;
        $product->brandId = $brandId;
        $product->price = $price;
        $product->discountPrice = $discountPrice;
        $product->quantity = $quantity;
        $product->image = $imageName;
        $product->description = $description;
        $product->status = $status;

        if ($productDAO->update($product)) {
            // Upload multiple images
            if (isset($_FILES["images"]["name"]) && is_array($_FILES["images"]["name"])) {
                $allowExtensions = ["jpg", "jpeg", "png", "gif", "webp"];
                $totalFiles = count($_FILES["images"]["name"]);
                for ($i = 0; $i < $totalFiles; $i++) {
                    $fName = $_FILES["images"]["name"][$i];
                    if ($fName != "") {
                        $tName = $_FILES["images"]["tmp_name"][$i];
                        $ext = strtolower(pathinfo($fName, PATHINFO_EXTENSION));
                        if (in_array($ext, $allowExtensions)) {
                            $galleryName = time() . "_" . $i . "_" . $slug . "." . $ext;
                            $gUploadPath = __DIR__ . "/../../../uploads/products/" . $galleryName;
                            if (move_uploaded_file($tName, $gUploadPath)) {
                                $productDAO->insertImage($id, $galleryName);
                            }
                        }
                    }
                }
            }

            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Cập nhật thất bại.";
        }
    }
}

$pageTitle = "Cập nhật sản phẩm";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Cập nhật sản phẩm</h5>
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
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="productName" class="form-control" value="<?= htmlspecialchars($product->proname) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($product->slug) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                    <select name="categoryId" class="form-select">
                        <option value="0">-- Chọn danh mục --</option>
                        <?php foreach($categories as $item): ?>
                            <option value="<?= $item->id ?>" <?= $item->id == $product->categoryId ? 'selected' : '' ?>>
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
                            <option value="<?= $item->id ?>" <?= $item->id == $product->brandId ? 'selected' : '' ?>>
                                <?= htmlspecialchars($item->brandname) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Giá bán <span class="text-danger">*</span></label>
                    <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($product->price) ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Giá khuyến mãi</label>
                    <input type="number" name="discountPrice" class="form-control" value="<?= htmlspecialchars($product->discountPrice) ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="quantity" class="form-control" value="<?= htmlspecialchars($product->quantity) ?>">
                </div>
                <div class="col-md-12 mb-3">
                    <div class="text-center mb-3" id="preview">
                        <?php if (!empty($product->image)): ?>
                            <img src="/MiniShop_VoThanhDat/uploads/products/<?= htmlspecialchars($product->image) ?>" class="img-thumbnail" width="150">
                        <?php endif; ?>
                    </div>
                    <label class="form-label">Hình ảnh mới</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Hình ảnh phụ (Gallery)</label>
                    <div class="d-flex flex-wrap mb-2">
                        <?php 
                        $gallery = $productDAO->getImagesByProductId($id);
                        foreach ($gallery as $img): 
                        ?>
                            <div class="position-relative me-2 mb-2">
                                <img src="/MiniShop_VoThanhDat/uploads/products/<?= htmlspecialchars($img->image) ?>" class="img-thumbnail" width="100">
                                <button type="submit" name="btnDeleteImage" value="1" onclick="document.getElementById('deleteImageId').value=<?= $img->id ?>; return confirm('Bạn có chắc chắn muốn xóa ảnh này?');" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1"><i class="fas fa-times"></i></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mb-3" id="preview-gallery"></div>
                    <label class="form-label">Thêm ảnh phụ mới</label>
                    <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple>
                    <input type="hidden" name="deleteImageId" id="deleteImageId" value="">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="5" class="form-control"><?= htmlspecialchars($product->description ?? "") ?></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label d-block">Trạng thái</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?= $product->status == 1 ? "checked" : "" ?>>
                        <label class="form-check-label" for="status1">Hiển thị</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?= $product->status == 0 ? "checked" : "" ?>>
                        <label class="form-check-label" for="status0">Ẩn</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
