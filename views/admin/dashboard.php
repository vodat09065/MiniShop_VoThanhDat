<?php
require_once __DIR__ . "/../../autoload.php";

use DAO\CategoryDAO;
use DAO\BrandDAO;
use DAO\ProductDAO;
use DAO\CustomerDAO;
use DAO\UserDAO;
use DAO\OrderDAO;

$categoryDAO = new \DAO\CategoryDAO();
$brandDAO = new \DAO\BrandDAO();
$productDAO = new \DAO\ProductDAO();
$customerDAO = new \DAO\CustomerDAO();
$userDAO = new \DAO\UserDAO();
$orderDAO = new \DAO\OrderDAO();

$totalCategories = count($categoryDAO->getAll());
$totalBrands = count($brandDAO->getAll());
$totalProducts = count($productDAO->getAll());
$totalCustomers = count($customerDAO->getAll());
$totalOrders = count($orderDAO->getAll());

$latestProducts = $productDAO->getLatest(5);
$latestOrders = $orderDAO->getLatest(5);

$pageTitle = "Dashboard";
ob_start();
?>
<div class="row mb-4">
    <div class="col-md-2">
        <div class="stat-card bg-customers">
            <div>
                <h3><?= $totalCustomers ?></h3>
                <p>Khách hàng</p>
            </div>
            <i class="fas fa-users stat-icon"></i>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-products">
            <div>
                <h3><?= $totalProducts ?></h3>
                <p>Sản phẩm</p>
            </div>
            <i class="fas fa-box stat-icon"></i>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card bg-orders">
            <div>
                <h3><?= $totalOrders ?></h3>
                <p>Đơn hàng</p>
            </div>
            <i class="fas fa-shopping-cart stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-users">
            <div>
                <h3><?= count($userDAO->getAll()) ?></h3>
                <p>Người dùng</p>
            </div>
            <i class="fas fa-user-shield stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-brands">
            <div>
                <h3><?= $totalBrands ?></h3>
                <p>Thương hiệu</p>
            </div>
            <i class="fas fa-tag stat-icon"></i>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Đơn hàng mới nhất</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Mã đơn</th>
                                <th>Khách hàng ID</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latestOrders as $index => $order): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($order->orderCode) ?></td>
                                <td><?= $order->customerId ?></td>
                                <td><?= number_format($order->totalAmount, 0, ',', '.') ?> đ</td>
                                <td>
                                    <?php if ($order->status == 0): ?>
                                        <span class="badge bg-warning">Chờ xử lý</span>
                                    <?php elseif ($order->status == 1): ?>
                                        <span class="badge bg-success">Hoàn thành</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Hủy</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (count($latestOrders) == 0): ?>
                            <tr><td colspan="5" class="text-center py-3">Chưa có đơn hàng nào</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Sản phẩm mới nhất</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latestProducts as $index => $product): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($product->proname) ?></td>
                                <td><?= number_format($product->price, 0, ',', '.') ?> đ</td>
                                <td>
                                    <?php if ($product->status == 1): ?>
                                        <span class="badge bg-success">Hiển thị</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (count($latestProducts) == 0): ?>
                            <tr><td colspan="4" class="text-center py-3">Chưa có sản phẩm nào</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "layouts/master.php";
?>
