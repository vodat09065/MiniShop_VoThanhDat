<div class="text-center py-5 bg-light mb-5 rounded shadow-sm">
  <h1 class="fw-bold text-primary">Chào mừng đến MiniShop</h1>
  <p class="text-muted fs-5">Website bán hàng trực tuyến uy tín và chất lượng</p>
  <a href="#new-products" class="btn btn-primary btn-lg mt-3 px-4 rounded-pill">Khám phá ngay</a>
</div>

<div class="container">
  <!-- Danh mục nổi bật -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 border-start border-primary border-4 ps-2">Danh mục nổi bật</h4>
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
    <h4 class="fw-bold m-0 border-start border-danger border-4 ps-2 text-danger">Đang giảm giá</h4>
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
    <h4 class="fw-bold m-0 border-start border-primary border-4 ps-2">Sản phẩm mới nhất</h4>
  </div>
  <div class="row">
    <?php foreach ($newProducts as $product): ?>
    <div class="col-6 col-md-3 mb-4">
      <?php require __DIR__ . '/../layouts/product-card.php'; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<style>
.category-card { transition: all 0.3s ease; border: 1px solid #f0f0f0 !important; }
.category-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; border-color: #0d6efd !important;}
</style>
