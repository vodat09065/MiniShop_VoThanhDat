<?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin();
require_once "../../../dao/UserDAO.php";

$userDAO = new UserDAO();
$keyword = "";
if (isset($_GET["keyword"])) {
    $keyword = trim($_GET["keyword"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnDelete"])) {
    CsrfMiddleware::verify();
    $id = $_POST["id"] ?? 0;
    if ($userDAO->delete($id)) {
        header("Location: index.php?msg=deleted");
        exit();
    } else {
        $error = "XÃ³a tháº¥t báº¡i! CÃ³ thá»ƒ ngÆ°á»i dÃ¹ng nÃ y Ä‘ang cÃ³ liÃªn káº¿t dá»¯ liá»‡u.";
    }
}

$limit = (int)($_GET["limit"] ?? 10);
$page = (int)($_GET["page"] ?? 1);
$offset = ($page - 1) * $limit;

$totalRecords = $userDAO->count("users", "username", $keyword);
$totalPages = ceil($totalRecords / $limit);

$users = $userDAO->getPage($limit, $offset, $keyword);
$pageTitle = "Quáº£n lÃ½ ngÆ°á»i dÃ¹ng";
ob_start();
?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Danh sÃ¡ch ngÆ°á»i dÃ¹ng</h5>
        <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> ThÃªm má»›i</a>
    </div>
    <div class="card-body">
        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">XÃ³a ngÆ°á»i dÃ¹ng thÃ nh cÃ´ng!</div>
        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endif; ?>
        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endif; ?>

        <form class="row mb-3" method="GET">
            <div class="col-md-4">
                <input type="text" name="keyword" class="form-control" placeholder="Há» tÃªn hoáº·c username..." value="<?= htmlspecialchars($keyword) ?>">
                <input type="hidden" name="limit" value="<?= $limit ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">TÃ¬m kiáº¿m</button>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <label class="me-2 text-nowrap">Hiá»ƒn thá»‹:</label>
                <form method="GET">
                    <input type="hidden" name="keyword" value="<?= htmlspecialchars($keyword) ?>">
                    <select name="limit" class="form-select" onchange="this.form.submit()">
                        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                        <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                        <option value="30" <?= $limit == 30 ? 'selected' : '' ?>>30</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Há» tÃªn</th>
                        <th>Username</th>
                        <th>Vai trÃ²</th>
                        <th>Tráº¡ng thÃ¡i</th>
                        <th>Chá»©c nÄƒng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); if (count($users) > 0): ?>
                        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); foreach ($users as $item): ?>
                        <tr>
                            <td><?= $item->id ?></td>
                            <td><?= htmlspecialchars($item->fullname) ?></td>
                            <td><?= htmlspecialchars($item->username) ?></td>
                            <td>
                                <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); if ($item->role == 1): ?>
                                    <span class="badge bg-danger">Quáº£n trá»‹</span>
                                <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); else: ?>
                                    <span class="badge bg-info text-dark">NhÃ¢n viÃªn</span>
                                <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endif; ?>
                            </td>
                            <td>
                                <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); if ($item->status == 1): ?>
                                    <span class="badge bg-success">Hoáº¡t Ä‘á»™ng</span>
                                <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); else: ?>
                                    <span class="badge bg-secondary">KhÃ³a</span>
                                <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endif; ?>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="detail.php?id=<?= $item->id ?>" class="btn btn-info btn-sm text-white"><i class="fas fa-eye"></i></a>
                                    <a href="edit.php?id=<?= $item->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form method="POST" onsubmit="return confirm('Báº¡n cÃ³ cháº¯c muá»‘n xÃ³a?');" style="display:inline;">
<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="id" value="<?= $item->id ?>">
                                        <button type="submit" name="btnDelete" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endforeach; ?>
                    <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-danger">KhÃ´ng tÃ¬m tháº¥y dá»¯ liá»‡u.</td>
                        </tr>
                    <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endif; ?>
                </tbody>
            </table>
        </div>

        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); if ($totalPages > 1): ?>
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&page=1">Äáº§u</a>
                </li>
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&page=<?= $page - 1 ?>">TrÆ°á»›c</a>
                </li>
                
                <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endfor; ?>
                
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&page=<?= $page + 1 ?>">Sau</a>
                </li>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?limit=<?= $limit ?>&keyword=<?= urlencode($keyword) ?>&page=<?= $totalPages ?>">Cuá»‘i</a>
                </li>
            </ul>
        </nav>
        <?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin(); endif; ?>

    </div>
</div>

<?php
require_once "../../../middleware/RoleMiddleware.php";
RoleMiddleware::checkAdmin();
$content = ob_get_clean();
include "../layouts/master.php";
?>

