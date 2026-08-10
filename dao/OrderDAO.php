<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Order.php";

class OrderDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(string $keyword = ""): array
    {
        $list = [];
        try {
            $sql = "SELECT o.*, c.fullname AS customerName, u.fullname AS userName 
                    FROM orders o
                    INNER JOIN customers c ON o.customer_id = c.id
                    LEFT JOIN users u ON o.user_id = u.id";
            if (!empty($keyword)) {
                $sql .= " WHERE o.order_code LIKE ? OR c.fullname LIKE ?";
            }
            $sql .= " ORDER BY o.id DESC";
            
            $stmt = $this->prepare($sql);
            if (!empty($keyword)) {
                $search = "%{$keyword}%";
                $stmt->bind_param("ss", $search, $search);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $order = new Order(
                    $row["customer_id"],
                    $row["user_id"],
                    $row["order_code"],
                    $row["total_amount"],
                    $row["note"],
                    $row["status"]
                );
                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];
                $order->customerName = $row["customerName"];
                $order->userName = $row["userName"] ?? "N/A";
                $list[] = $order;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    public function getLatest(int $limit): array { 
        $list = []; 
        try { 
            $sql = "SELECT o.*, c.fullname AS customerName, u.fullname AS userName 
                    FROM orders o
                    INNER JOIN customers c ON o.customer_id = c.id
                    LEFT JOIN users u ON o.user_id = u.id 
                    ORDER BY o.id DESC LIMIT ?"; 
            $stmt = $this->prepare($sql); 
            $stmt->bind_param("i", $limit); 
            $stmt->execute(); 
            $result = $stmt->get_result(); 
            while ($row = $result->fetch_assoc()) { 
                $order = new Order(
                    $row["customer_id"], 
                    $row["user_id"], 
                    $row["order_code"], 
                    $row["total_amount"], 
                    $row["note"], 
                    $row["status"]
                ); 
                $order->id = $row["id"]; 
                $order->createdAt = $row["created_at"]; 
                $order->updatedAt = $row["updated_at"]; 
                $order->customerName = $row["customerName"];
                $order->userName = $row["userName"] ?? "N/A";
                $list[] = $order; 
            } 
        } catch (Exception $e) { 
            throw $e; 
        } 
        return $list; 
    }

    public function findById(int $id): ?Order
    {
        try {
            $sql = "SELECT o.*, c.fullname AS customerName, u.fullname AS userName 
                    FROM orders o
                    INNER JOIN customers c ON o.customer_id = c.id
                    LEFT JOIN users u ON o.user_id = u.id 
                    WHERE o.id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $order = new Order(
                    $row["customer_id"],
                    $row["user_id"],
                    $row["order_code"],
                    $row["total_amount"],
                    $row["note"],
                    $row["status"]
                );
                $order->id = $row["id"];
                $order->createdAt = $row["created_at"];
                $order->updatedAt = $row["updated_at"];
                $order->customerName = $row["customerName"];
                $order->userName = $row["userName"] ?? "N/A";
                return $order;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function updateStatus(int $id, int $status): bool
    {
        try {
            $sql = "UPDATE orders SET status=? WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("ii", $status, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
