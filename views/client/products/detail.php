<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none"><?= htmlspecialchars($product->cateName ?? 'Danh mục') ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product->proname) ?></li>
        </ol>
    </nav>

    <div class="row bg-white p-4 rounded-4 shadow-sm border">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-5 mb-4 mb-md-0 text-center">
            <img src="<?= PRODUCT_IMAGE_URL . $product->image ?>" class="img-fluid rounded p-3" alt="<?= htmlspecialchars($product->proname) ?>" style="max-height: 450px; object-fit: contain; width: 100%;">
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-7">
            <h2 class="fw-bold mb-3"><?= htmlspecialchars($product->proname) ?></h2>
            
            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-primary px-3 py-2 me-2 fs-6 rounded-pill"><?= htmlspecialchars($product->brandName ?? 'Thương hiệu') ?></span>
                <span class="text-muted small"><i class="bi bi-upc-scan me-1"></i> Mã SP: <?= $product->id ?></span>
            </div>

            <div class="mb-4 p-4 bg-light rounded-3 border-start border-primary border-4 shadow-sm">
                <?php if($product->discountPrice > 0): ?>
                    <div class="d-flex align-items-end">
                        <h2 class="text-danger fw-bold m-0 me-3"><?= number_format($product->discountPrice) ?> ₫</h2>
                        <del class="text-muted fs-5 mb-1"><?= number_format($product->price) ?> ₫</del>
                    </div>
                <?php else: ?>
                    <h2 class="text-danger fw-bold m-0"><?= number_format($product->price) ?> ₫</h2>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold border-bottom pb-2 mb-3">Mô tả sản phẩm</h5>
                <div class="text-muted product-description" style="line-height: 1.7;">
                    <?= nl2br(htmlspecialchars($product->description)) ?>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-3 mt-4 pt-4 border-top">
                <div class="input-group input-group-lg" style="width: 140px;">
                    <button class="btn btn-outline-secondary" type="button" id="btn-minus"><i class="bi bi-dash"></i></button>
                    <input type="text" class="form-control text-center bg-white" value="1" id="quantity" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="btn-plus"><i class="bi bi-plus"></i></button>
                </div>
                <button class="btn btn-primary btn-lg px-4 flex-grow-1 btn-add-cart" data-productid="<?= $product->id ?>">
                    <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ hàng
                </button>
            </div>
        </div>
    </div>
</div>

