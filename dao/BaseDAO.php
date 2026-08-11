<?php
require_once __DIR__ . "/../config/Database.php";

class BaseDAO extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    // Thực thi câu lệnh SELECT
    protected function executeQuery(string $sql): mysqli_result|false
    {
        return $this->conn->query($sql);
    }

    // Chuẩn bị câu lệnh Prepared Statement
    protected function prepare(string $sql): mysqli_stmt|false
    {
        return $this->conn->prepare($sql);
    }

    // Bắt đầu Transaction
    protected function beginTransaction(): void
    {
        $this->conn->begin_transaction();
    }

    // Xác nhận Transaction
    protected function commit(): void
    {
        $this->conn->commit();
    }

    // Hủy Transaction
    protected function rollback(): void
    {
        $this->conn->rollback();
    }

    // Đóng kết nối
    public function close(): void
    {
        $this->conn->close();
    }

    // Lấy tổng số bản ghi
    public function count(string $table, string $column = "", string $keyword = ""): int
    {
        if ($keyword == "") {
            $sql = "SELECT COUNT(*) AS total FROM $table";
            $result = $this->conn->query($sql);
            if ($result && $row = $result->fetch_assoc()) {
                return (int)$row["total"];
            }
            return 0;
        }

        $sql = "SELECT COUNT(*) AS total FROM $table WHERE $column LIKE ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $searchTerm = "%$keyword%";
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return (int)$row["total"];
            }
        }
        return 0;
    }
}
