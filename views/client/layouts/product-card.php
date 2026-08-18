<div class="card h-100 shadow-sm">
  <img src="<?= PRODUCT_IMAGE_URL . $product->image ?>" class="card-img-top" alt="<?= htmlspecialchars($product->proname) ?>" style="height: 200px; object-fit: contain; padding: 15px;">
  <div class="card-body d-flex flex-column">
    <h6 class="card-title text-truncate" title="<?= htmlspecialchars($product->proname) ?>">
      <a href="<?= BASE_URL ?>product/<?= $product->slug ?>" class="text-dark text-decoration-none">
        <?= htmlspecialchars($product->proname) ?>
      </a>
    </h6>
    
    <div class="mt-auto">
        <?php if($product->discountPrice > 0): ?>
          <del class="text-muted small"><?= number_format($product->price) ?> đ</del>
          <p class="text-danger fw-bold mb-2"><?= number_format($product->discountPrice) ?> đ</p>
        <?php else: ?>
          <p class="text-danger fw-bold mb-2"><?= number_format($product->price) ?> đ</p>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between align-items-center">
          <small class="text-muted"><i class="bi bi-tag"></i> <?= htmlspecialchars($product->brandName ?? '') ?></small>
          <div class="btn-group">
            <a href="<?= BASE_URL ?>product/<?= $product->slug ?>" class="btn btn-outline-secondary btn-sm rounded-start-pill px-3" title="Xem chi tiết">
              <i class="bi bi-eye"></i>
            </a>
            <button type="button" class="btn btn-primary btn-sm rounded-end-pill px-3 btn-add-cart" data-productid="<?= $product->id ?>" title="Thêm vào giỏ">
              <i class="bi bi-cart-plus"></i>
            </button>
          </div>
        </div>
    </div>
  </div>
</div>
