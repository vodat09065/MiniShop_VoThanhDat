<?php
namespace Controllers\Client;

use DAO\ProductDAO;
use DAO\CategoryDAO;

class HomeController
{
    private ProductDAO $productDAO;
    private CategoryDAO $categoryDAO;

    public function __construct()
    {
        $this->productDAO = new ProductDAO();
        $this->categoryDAO = new CategoryDAO();
    }

    public function index()
    {
        $title = "Trang chủ";
        
        // Danh mục
        $categories = $this->categoryDAO->getAll();
        
        // Sản phẩm giảm giá (mặc định không truyền – lấy 8 sản phẩm)
        $discountProducts = $this->productDAO->getDiscountProducts(8);
        
        // Sản phẩm mới (4 sản phẩm)
        $newProducts = $this->productDAO->getNewProducts(4);

        ob_start();
        require __DIR__ . "/../../views/client/home/index.php";
        $content = ob_get_clean();
        
        require __DIR__ . "/../../views/client/layouts/master.php";
    }
}
