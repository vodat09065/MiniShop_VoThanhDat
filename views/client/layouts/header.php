<?php
use Composers\HeaderComposer;

$headerData = HeaderComposer::compose();
$categories = $headerData['categories'];
$brands = $headerData['brands'];
?>
<nav class="navbar navbar-expand-lg navbar-light sticky-top py-3">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
      <i class="bi bi-shop me-1"></i> MiniShop
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Danh mục</a>
          <ul class="dropdown-menu">
            <?php foreach ($categories as $category): ?>
            <li>
              <a class="dropdown-item" href="<?= BASE_URL ?>category/<?= $category->slug ?>">
                <?= htmlspecialchars($category->catename) ?>
              </a>
            </li>
            <?php endforeach; ?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Xem tất cả</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Thương hiệu</a>
          <ul class="dropdown-menu">
            <?php foreach ($brands as $brand): ?>
            <li>
              <a class="dropdown-item" href="<?= BASE_URL ?>brand/<?= $brand->slug ?>">
                <?= htmlspecialchars($brand->brandname) ?>
              </a>
            </li>
            <?php endforeach; ?>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Xem tất cả</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex me-4" action="<?= BASE_URL ?>search" method="GET">
        <input class="form-control me-2" type="search" name="keyword" placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
      </form>
      <div class="d-flex align-items-center">
        <a href="#" class="nav-icon me-4"><i class="bi bi-person fs-5"></i></a>
        <a href="<?= BASE_URL ?>cart" class="nav-icon position-relative">
          <i class="bi bi-cart fs-5"></i>
          <?php
          $cartCount = 0;
          if (isset($_SESSION[CART_SESSION_KEY])) {
              foreach ($_SESSION[CART_SESSION_KEY] as $item) {
                  $cartCount += $item['quantity'];
              }
          }
          ?>
          <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"><?= $cartCount ?></span>
        </a>
      </div>
    </div>
  </div>
</nav>
