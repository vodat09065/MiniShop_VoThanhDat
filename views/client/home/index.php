<div class="hero-section text-center">
  <h1 class="fw-bold">Khám Phá Công Nghệ Đỉnh Cao</h1>
  <p class="mt-3 mb-4">Trải nghiệm các sản phẩm công nghệ tiên tiến nhất với mức giá không thể tốt hơn tại MiniShop.</p>
  <a href="#new-products" class="btn btn-primary btn-lg rounded-pill px-5">Bắt đầu mua sắm <i class="bi bi-arrow-right ms-2"></i></a>
</div>

<div class="container pb-5">
  <!-- Danh mục nổi bật -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="section-title m-0">Danh mục nổi bật</h3>
  </div>
  <div class="row">
    <?php foreach ($categories as $category): ?>
    <div class="col-6 col-md-3 mb-4">
      <a href="<?= BASE_URL ?>category/<?= $category->slug ?>" class="text-decoration-none">
        <div class="card h-100 border-0 shadow-sm category-card text-center py-4 bg-white rounded-3">
          <div class="card-body">
            <h5 class="card-title text-dark m-0 fw-semibold"><?= htmlspecialchars($category->catename) ?></h5>
          </div>
        </div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Sản phẩm giảm giá -->
  <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
    <h3 class="section-title sale m-0 text-danger">Ưu Đãi Khủng</h3>
  </div>
  <div class="row">
    <?php foreach ($discountProducts as $product): ?>
    <div class="col-6 col-md-3 mb-4">
      <?php require __DIR__ . '/../layouts/product-card.php'; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Sản phẩm mới nhất -->
  <div class="d-flex justify-content-between align-items-center mb-4 mt-5" id="new-products">
    <h3 class="section-title m-0">Sản phẩm mới nhất</h3>
  </div>
  <div class="row">
    <?php foreach ($newProducts as $product): ?>
    <div class="col-6 col-md-3 mb-4">
      <?php require __DIR__ . '/../layouts/product-card.php'; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>
