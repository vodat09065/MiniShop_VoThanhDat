<?php
namespace Controllers\Admin;

use DAO\ProductDAO;
use Middleware\CsrfMiddleware;

class ProductController
{
    public function index()
    {
        $productDAO = new ProductDAO();
        $keyword = trim($_GET["keyword"] ?? "");
        $error = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["btnDelete"])) {
            CsrfMiddleware::verify();
            $id = $_POST["id"] ?? 0;
            if ($productDAO->delete($id)) {
                header("Location: index.php?area=admin&controller=product&action=index&msg=deleted");
                exit();
            } else {
                $error = "Xóa thất bại! Sản phẩm có thể đang nằm trong đơn hàng.";
            }
        }

        $limit = (int)($_GET["limit"] ?? 10);
        $page = (int)($_GET["page"] ?? 1);
        $offset = ($page - 1) * $limit;
        $sort = $_GET["sort"] ?? "";

        $totalRecords = $productDAO->count("products", "proname", $keyword);
        $totalPages = ceil($totalRecords / $limit) ?: 1;

        $products = $productDAO->getPage($limit, $offset, $keyword, $sort);
        $pageTitle = "Quản lý sản phẩm";

        // Gọi View
        require __DIR__ . "/../../views/admin/products/index.php";
    }
}
