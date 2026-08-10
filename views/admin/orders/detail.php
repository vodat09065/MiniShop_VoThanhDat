<?php
require_once "../../../dao/OrderDAO.php";
require_once "../../../dao/OrderDetailDAO.php";

$orderDAO = new OrderDAO();
$orderDetailDAO = new OrderDetailDAO();

$id = $_GET["id"] ?? 0;
$order = $orderDAO->findById($id);

if (!$order) {
    die("Đơn hàng không tồn tại.");
}

$details = $orderDetailDAO->getByOrderId($id);
$pageTitle = "Chi tiết đơn hàng";
ob_start();

function getStatusBadge($status) {
    switch ($status) {
        case 0: return '<span class="badge bg-warning">Chờ xác nhận</span>';
        case 1: return '<span class="badge bg-info text-dark">Đã xác nhận</span>';
        case 2: return '<span class="badge bg-primary">Đang giao</span>';
        case 3: return '<span class="badge bg-success">Hoàn thành</span>';
        case 4: return '<span class="badge bg-danger">Đã hủy</span>';
        default: return '<span class="badge bg-secondary">Unknown</span>';
    }
}
?>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Thông tin đơn hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Mã đơn hàng:</strong> <?= htmlspecialchars($order->orderCode) ?></p>
                <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order->customerName) ?></p>
                <p><strong>Nhân viên xử lý:</strong> <?= htmlspecialchars($order->userName) ?></p>
                <p><strong>Trạng thái:</strong> <?= getStatusBadge($order->status) ?></p>
                <p><strong>Ngày đặt:</strong> <?= $order->createdAt ?></p>
                <p><strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($order->note ?? "Không có")) ?></p>
                <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold fs-5"><?= number_format($order->totalAmount, 0, ',', '.') ?> đ</span></p>
                <a href="update_status.php?id=<?= $order->id ?>" class="btn btn-primary w-100 mb-2">Cập nhật trạng thái</a>
                <a href="index.php" class="btn btn-secondary w-100">Quay lại danh sách</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Danh sách sản phẩm</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Ảnh</th>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($details) > 0): ?>
                                <?php foreach($details as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <?php if (!empty($item->productImage)): ?>
                                            <img src="../../uploads/products/<?= htmlspecialchars($item->productImage) ?>" width="50" height="50" style="object-fit:cover;">
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($item->productName) ?></td>
                                    <td><?= number_format($item->price, 0, ',', '.') ?> đ</td>
                                    <td><?= $item->quantity ?></td>
                                    <td class="text-danger fw-bold"><?= number_format($item->subtotal, 0, ',', '.') ?> đ</td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center text-danger">Không có sản phẩm nào.</td></tr>
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
include "../layouts/master.php";
?>
