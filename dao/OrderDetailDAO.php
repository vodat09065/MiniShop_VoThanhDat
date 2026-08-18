<?php
namespace DAO;

use Models\OrderDetail;
use \Exception;
class OrderDetailDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByOrderId(int $orderId): array
    {
        $list = [];
        try {
            $sql = "SELECT od.*, p.proname AS productName, p.image AS productImage 
                    FROM order_details od
                    INNER JOIN products p ON od.product_id = p.id
                    WHERE od.order_id = ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $detail = new OrderDetail(
                    $row["order_id"],
                    $row["product_id"],
                    $row["quantity"],
                    $row["price"],
                    $row["subtotal"]
                );
                $detail->id = $row["id"];
                $detail->createdAt = $row["created_at"];
                // Dynamic properties for view
                $detail->productName = $row["productName"];
                $detail->productImage = $row["productImage"];
                $list[] = $detail;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    public function insert(OrderDetail $detail): bool
    {
        try {
            $sql = "INSERT INTO order_details(order_id, product_id, quantity, price, subtotal)
                    VALUES(?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iiidd",
                $detail->orderId,
                $detail->productId,
                $detail->quantity,
                $detail->price,
                $detail->subtotal
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
