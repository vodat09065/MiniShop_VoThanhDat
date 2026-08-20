<?php
namespace Controllers\Client;

use DAO\ProductDAO;
use DAO\CustomerDAO;
use DAO\OrderDAO;
use DAO\OrderDetailDAO;
use Models\Customer;
use Models\Order;
use Models\OrderDetail;

class CartController
{
    private ProductDAO $productDAO;
    private CustomerDAO $customerDAO;
    private OrderDAO $orderDAO;
    private OrderDetailDAO $orderDetailDAO;

    public function __construct()
    {
        $this->productDAO = new ProductDAO();
        $this->customerDAO = new CustomerDAO();
        $this->orderDAO = new OrderDAO();
        $this->orderDetailDAO = new OrderDetailDAO();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function add()
    {
        if (!isset($_SESSION[CART_SESSION_KEY])) {
            $_SESSION[CART_SESSION_KEY] = [];
        }

        $productid = $_REQUEST["productid"] ?? null;
        if (!$productid) {
            header("Location: " . BASE_URL . "?error=invalid_product");
            exit;
        }

        $product = $this->productDAO->findById($productid);
        if (!$product) {
            header("Location: " . BASE_URL . "?error=product_not_found");
            exit;
        }

        $price = $product->discountPrice > 0 ? $product->discountPrice : $product->price;
        $quantity = isset($_REQUEST["quantity"]) ? (int)$_REQUEST["quantity"] : 1;
        if ($quantity < 1) $quantity = 1;

        if (isset($_SESSION[CART_SESSION_KEY][$productid])) {
            $_SESSION[CART_SESSION_KEY][$productid]["quantity"] += $quantity;
        } else {
            $_SESSION[CART_SESSION_KEY][$productid] = [
                "productid" => $product->id,
                "productname" => $product->proname,
                "image" => $product->image,
                "price" => $price,
                "quantity" => $quantity
            ];
        }

        // Redirect to Cart page
        header("Location: " . BASE_URL . "cart");
        exit;
    }

    public function index()
    {
        $cart = $_SESSION[CART_SESSION_KEY] ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item["price"] * $item["quantity"];
        }
        $title = "Giỏ hàng của bạn";

        ob_start();
        require __DIR__ . "/../../views/client/cart/index.php";
        $content = ob_get_clean();
        
        require __DIR__ . "/../../views/client/layouts/master.php";
    }

    public function update()
    {
        if (!isset($_SESSION[CART_SESSION_KEY])) {
            $_SESSION[CART_SESSION_KEY] = [];
        }

        $productid = $_REQUEST["productid"] ?? null;
        $quantity = isset($_REQUEST["quantity"]) ? (int)$_REQUEST["quantity"] : null;

        if (!$productid || $quantity === null) {
            header("Location: " . BASE_URL . "cart?error=invalid_data");
            exit;
        }
        
        if ($quantity < 1) {
            unset($_SESSION[CART_SESSION_KEY][$productid]);
            header("Location: " . BASE_URL . "cart");
            exit;
        }

        if (isset($_SESSION[CART_SESSION_KEY][$productid])) {
            $_SESSION[CART_SESSION_KEY][$productid]["quantity"] = $quantity;
            header("Location: " . BASE_URL . "cart");
            exit;
        }

        header("Location: " . BASE_URL . "cart");
        exit;
    }

    public function remove()
    {
        if (!isset($_SESSION[CART_SESSION_KEY])) {
            $_SESSION[CART_SESSION_KEY] = [];
        }

        $productid = $_REQUEST["productid"] ?? null;

        if (!$productid) {
            header("Location: " . BASE_URL . "cart");
            exit;
        }

        if (isset($_SESSION[CART_SESSION_KEY][$productid])) {
            unset($_SESSION[CART_SESSION_KEY][$productid]);
        }

        header("Location: " . BASE_URL . "cart");
        exit;
    }

    public function checkout()
    {
        $cart = $_SESSION[CART_SESSION_KEY] ?? [];
        if (empty($cart)) {
            header("Location: " . BASE_URL . "cart?error=empty_cart");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fullname = $_POST["fullname"] ?? "";
            $phone = $_POST["phone"] ?? "";
            $address = $_POST["address"] ?? "";
            $note = $_POST["note"] ?? "";

            if (empty($fullname) || empty($phone) || empty($address)) {
                header("Location: " . BASE_URL . "cart?error=missing_info");
                exit;
            }

            $userId = $_SESSION["user"]->id ?? 0;

            try {
                $this->customerDAO->beginTransaction();

                // 1. Tìm hoặc tạo Customer
                $customer = $this->customerDAO->findByPhone($phone);
                if (!$customer) {
                    $customer = new Customer($fullname, $phone, "", $address, $note);
                    $customerId = $this->customerDAO->insert($customer);
                    if (!$customerId) throw new \Exception("Không thể tạo khách hàng mới");
                } else {
                    $customerId = $customer->id;
                    // Cập nhật thông tin nếu cần
                    $customer->fullname = $fullname;
                    $customer->address = $address;
                    $customer->note = $note;
                    $this->customerDAO->update($customer);
                }

                // 2. Lưu Order
                $totalAmount = 0;
                foreach ($cart as $item) {
                    $totalAmount += $item["price"] * $item["quantity"];
                }

                $orderCode = "ORD-" . strtoupper(uniqid());
                $order = new Order($customerId, $userId, $orderCode, $totalAmount, $note, 0);
                $orderId = $this->orderDAO->insert($order);
                if (!$orderId) throw new \Exception("Không thể tạo đơn hàng");

                // 3. Lưu OrderDetail
                foreach ($cart as $item) {
                    $subtotal = $item["price"] * $item["quantity"];
                    $detail = new OrderDetail($orderId, $item["productid"], $item["quantity"], $item["price"], $subtotal);
                    if (!$this->orderDetailDAO->insert($detail)) {
                        throw new \Exception("Không thể lưu chi tiết đơn hàng");
                    }
                }

                $this->customerDAO->commit();

                // Clear cart
                $_SESSION[CART_SESSION_KEY] = [];
                
                // Show success view
                $title = "Đặt hàng thành công";
                ob_start();
                echo "<div class='container py-5 text-center'>
                        <div class='mb-4'>
                            <i class='bi bi-check-circle text-success' style='font-size: 5rem;'></i>
                        </div>
                        <h2 class='fw-bold'>Đặt hàng thành công!</h2>
                        <p class='text-muted fs-5'>Mã đơn hàng của bạn là: <strong>{$orderCode}</strong></p>
                        <p>Cảm ơn bạn đã mua sắm tại MiniShop. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.</p>
                        <a href='" . BASE_URL . "' class='btn btn-primary mt-3'>Tiếp tục mua sắm</a>
                      </div>";
                $content = ob_get_clean();
                require __DIR__ . "/../../views/client/layouts/master.php";
                exit;

            } catch (\Exception $e) {
                $this->customerDAO->rollback();
                header("Location: " . BASE_URL . "cart?error=" . urlencode($e->getMessage()));
                exit;
            }
        }
    }
}
