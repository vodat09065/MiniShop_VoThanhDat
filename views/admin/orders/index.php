<?php
require_once "../../dao/OrderDAO.php";

$orderDAO = new OrderDAO();
$keyword = "";
if (isset($_GET["keyword"])) {
    $keyword = trim($_GET["keyword"]);
}

$orders = $orderDAO->getAll($keyword);
$pageTitle = "Quản lý đơn hàng";
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

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Danh sách đơn hàng</h5>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">Cập nhật trạng thái đơn hàng thành công!</div>
        <?php endif; ?>

        <form class="row mb-3" method="GET">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Mã đơn hàng, tên khách hàng..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Nhân viên</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $item): ?>
                        <tr>
                            <td><span class="fw-bold"><?= htmlspecialchars($item->orderCode) ?></span></td>
                            <td><?= htmlspecialchars($item->customerName) ?></td>
                            <td><?= htmlspecialchars($item->userName) ?></td>
                            <td><?= $item->createdAt ?></td>
                            <td class="text-danger fw-bold"><?= number_format($item->totalAmount, 0, ',', '.') ?> đ</td>
                            <td><?= getStatusBadge($item->status) ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="detail.php?id=<?= $item->id ?>" class="btn btn-info btn-sm text-white"><i class="fas fa-eye"></i> Chi tiết</a>
                                    <a href="update_status.php?id=<?= $item->id ?>" class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i> Cập nhật TT</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-danger">Không tìm thấy dữ liệu.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
