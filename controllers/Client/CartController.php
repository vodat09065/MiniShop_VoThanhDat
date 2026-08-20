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

        $productid = $_POST["productid"] ?? null;
        if (!$productid) {
            echo json_encode(["success" => false, "message" => "Sản phẩm không hợp lệ"]);
            exit;
        }

        $product = $this->productDAO->findById($productid);
        if (!$product) {
            echo json_encode(["success" => false, "message" => "Không tìm thấy sản phẩm"]);
            exit;
        }

        $price = $product->discountPrice > 0 ? $product->discountPrice : $product->price;
        $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : 1;
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

        $cartCount = 0;
        foreach ($_SESSION[CART_SESSION_KEY] as $item) {
            $cartCount += $item["quantity"];
        }

        echo json_encode([
            "success" => true,
            "message" => "Đã thêm sản phẩm vào giỏ hàng",
            "cartCount" => $cartCount
        ]);
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

        $productid = $_POST["productid"] ?? null;
        $quantity = isset($_POST["quantity"]) ? (int)$_POST["quantity"] : null;

        if (!$productid || $quantity === null || $quantity < 1) {
            echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ"]);
            exit;
        }

        if (isset($_SESSION[CART_SESSION_KEY][$productid])) {
            $_SESSION[CART_SESSION_KEY][$productid]["quantity"] = $quantity;
            
            $cartCount = 0;
            $cartTotal = 0;
            $itemTotal = $_SESSION[CART_SESSION_KEY][$productid]["price"] * $quantity;
            
            foreach ($_SESSION[CART_SESSION_KEY] as $item) {
                $cartCount += $item["quantity"];
                $cartTotal += $item["price"] * $item["quantity"];
            }

            echo json_encode([
                "success" => true,
                "cartCount" => $cartCount,
                "cartTotal" => number_format($cartTotal) . " ₫",
                "itemTotal" => number_format($itemTotal) . " ₫"
            ]);
            exit;
        }

        echo json_encode(["success" => false, "message" => "Sản phẩm không có trong giỏ hàng"]);
        exit;
    }

    public function remove()
    {
        if (!isset($_SESSION[CART_SESSION_KEY])) {
            $_SESSION[CART_SESSION_KEY] = [];
        }

        $productid = $_POST["productid"] ?? null;

        if (!$productid) {
            echo json_encode(["success" => false, "message" => "Dữ liệu không hợp lệ"]);
            exit;
        }

        if (isset($_SESSION[CART_SESSION_KEY][$productid])) {
            unset($_SESSION[CART_SESSION_KEY][$productid]);
            
            $cartCount = 0;
            $cartTotal = 0;
            foreach ($_SESSION[CART_SESSION_KEY] as $item) {
                $cartCount += $item["quantity"];
                $cartTotal += $item["price"] * $item["quantity"];
            }

            echo json_encode([
                "success" => true,
                "message" => "Đã xóa sản phẩm khỏi giỏ hàng",
                "cartCount" => $cartCount,
                "cartTotal" => number_format($cartTotal) . " ₫"
            ]);
            exit;
        }

        echo json_encode(["success" => false, "message" => "Sản phẩm không có trong giỏ hàng"]);
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
