<?php
require_once "../../../dao/OrderDAO.php";

$orderDAO = new OrderDAO();
$id = $_GET["id"] ?? 0;
$order = $orderDAO->findById($id);

if (!$order) {
    die("Đơn hàng không tồn tại.");
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    CsrfMiddleware::verify();
    $status = $_POST["status"] ?? 0;

    if ($orderDAO->updateStatus($id, $status)) {
        header("Location: index.php?msg=updated");
        exit();
    } else {
        $errors[] = "Cập nhật trạng thái thất bại. Vui lòng thử lại.";
    }
}

$pageTitle = "Cập nhật trạng thái đơn hàng";
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Cập nhật trạng thái - Đơn: <?= htmlspecialchars($order->orderCode) ?></h5>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <div class="mb-4">
                        <label class="form-label d-block fw-bold">Chọn trạng thái mới:</label>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?= $order->status == 0 ? "checked" : "" ?>>
                            <label class="form-check-label" for="status0"><span class="badge bg-warning text-dark">Chờ xác nhận</span></label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?= $order->status == 1 ? "checked" : "" ?>>
                            <label class="form-check-label" for="status1"><span class="badge bg-info text-dark">Đã xác nhận</span></label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="status" id="status2" value="2" <?= $order->status == 2 ? "checked" : "" ?>>
                            <label class="form-check-label" for="status2"><span class="badge bg-primary">Đang giao</span></label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="status" id="status3" value="3" <?= $order->status == 3 ? "checked" : "" ?>>
                            <label class="form-check-label" for="status3"><span class="badge bg-success">Hoàn thành</span></label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="status" id="status4" value="4" <?= $order->status == 4 ? "checked" : "" ?>>
                            <label class="form-check-label" for="status4"><span class="badge bg-danger">Đã hủy</span></label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include "../layouts/master.php";
?>
