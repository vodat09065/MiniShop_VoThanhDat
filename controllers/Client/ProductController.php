<?php
namespace Controllers\Client;

use DAO\ProductDAO;

class ProductController
{
    private ProductDAO $productDAO;

    public function __construct()
    {
        $this->productDAO = new ProductDAO();
    }

    // Sản phẩm theo danh mục
    public function category()
    {
        $slug = $_GET['slug'] ?? '';
        $products = $this->productDAO->getByCategory($slug);
        
        $title = "Sản phẩm theo danh mục";

        ob_start();
        require __DIR__ . '/../../views/client/products/index.php';
        $content = ob_get_clean();
        
        require __DIR__ . "/../../views/client/layouts/master.php";
    }

    // Sản phẩm theo thương hiệu
    public function brand()
    {
        $slug = $_GET['slug'] ?? '';
        $products = $this->productDAO->getByBrand($slug);
        
        $title = "Sản phẩm theo thương hiệu";

        ob_start();
        require __DIR__ . '/../../views/client/products/index.php';
        $content = ob_get_clean();
        
        require __DIR__ . "/../../views/client/layouts/master.php";
    }

    // Chi tiết sản phẩm
    public function detail()
    {
        $slug = $_GET['slug'] ?? '';
        $product = $this->productDAO->getBySlug($slug);
        
        if (!$product) {
            die("Không tìm thấy sản phẩm"); // Có thể chuyển hướng về trang 404
        }
        
        $title = $product->proname;

        ob_start();
        require __DIR__ . '/../../views/client/products/detail.php';
        $content = ob_get_clean();
        
        require __DIR__ . "/../../views/client/layouts/master.php";
    }

    // Tìm kiếm sản phẩm
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $products = $this->productDAO->search($keyword);
        
        $title = "Tìm kiếm: " . htmlspecialchars($keyword);

        ob_start();
        require __DIR__ . '/../../views/client/products/index.php';
        $content = ob_get_clean();
        
        require __DIR__ . "/../../views/client/layouts/master.php";
    }
}
