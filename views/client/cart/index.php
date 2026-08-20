<div class="container py-5">
    <h3 class="fw-bold mb-4">Giỏ hàng của bạn</h3>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>
    <?php if (empty($cart)): ?>
        <div class="alert alert-warning shadow-sm">
            Giỏ hàng của bạn đang trống. <a href="<?= BASE_URL ?>" class="alert-link">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="table-responsive bg-white rounded shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $item): ?>
                                <tr id="cart-item-<?= $item['productid'] ?>">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= PRODUCT_IMAGE_URL . $item['image'] ?>" alt="" style="width: 60px; height: 60px; object-fit: cover;" class="me-3 rounded border">
                                            <span class="fw-medium text-dark"><?= htmlspecialchars($item['productname']) ?></span>
                                        </div>
                                    </td>
                                    <td><span class="text-danger fw-medium"><?= number_format($item['price']) ?> ₫</span></td>
                                    <td>
                                        <div class="input-group input-group-sm" style="width: 100px;">
                                            <a href="<?= BASE_URL ?>cart/update?productid=<?= $item['productid'] ?>&quantity=<?= $item['quantity'] - 1 ?>" class="btn btn-outline-secondary">-</a>
                                            <input type="text" class="form-control text-center fw-bold" value="<?= $item['quantity'] ?>" readonly>
                                            <a href="<?= BASE_URL ?>cart/update?productid=<?= $item['productid'] ?>&quantity=<?= $item['quantity'] + 1 ?>" class="btn btn-outline-secondary">+</a>
                                        </div>
                                    </td>
                                    <td class="item-subtotal-<?= $item['productid'] ?> fw-bold"><?= number_format($item['price'] * $item['quantity']) ?> ₫</td>
                                    <td>
                                        <a href="<?= BASE_URL ?>cart/remove?productid=<?= $item['productid'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="<?= BASE_URL ?>" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm</a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card bg-white border-0 shadow-sm rounded">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold border-bottom pb-3 mb-3">Tóm tắt đơn hàng</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tạm tính:</span>
                            <strong id="cartTotal" class="fs-5 text-danger"><?= number_format($total) ?> ₫</strong>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-3">Thông tin giao hàng</h6>
                            <form action="<?= BASE_URL ?>checkout" method="POST">
                                <div class="mb-3">
                                    <input type="text" name="fullname" class="form-control" placeholder="Họ và tên (*)" required value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']->fullname) : '' ?>">
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="phone" class="form-control" placeholder="Số điện thoại (*)" required value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']->phone) : '' ?>">
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="address" class="form-control" placeholder="Địa chỉ giao hàng (*)" required value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']->address) : '' ?>">
                                </div>
                                <div class="mb-4">
                                    <textarea name="note" class="form-control" rows="3" placeholder="Ghi chú đơn hàng (không bắt buộc)"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold text-uppercase">Xác nhận đặt hàng</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
