<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 border-start border-primary border-4 ps-2"><?= $title ?? "Danh sách sản phẩm" ?></h4>
  </div>
  
  <div class="row">
    <?php if (empty($products)): ?>
    <div class="col-12">
      <div class="alert alert-warning d-flex align-items-center shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
        <div>
           <h5 class="alert-heading mb-1">Không tìm thấy sản phẩm!</h5>
           <p class="mb-0">Rất tiếc, hiện tại không có sản phẩm nào phù hợp với yêu cầu của bạn.</p>
        </div>
      </div>
      <a href="<?= BASE_URL ?>" class="btn btn-primary mt-2 rounded-pill px-4">
        <i class="bi bi-house-door me-2"></i> Quay lại trang chủ
      </a>
    </div>
    <?php else: ?>
      <?php foreach ($products as $product): ?>
      <div class="col-6 col-md-3 mb-4">
        <?php require __DIR__ . '/../layouts/product-card.php'; ?>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
