<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Customer.php";

class CustomerDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(string $keyword = ""): array
    {
        $list = [];
        try {
            $sql = "SELECT * FROM customers";
            if (!empty($keyword)) {
                $sql .= " WHERE fullname LIKE ? OR phone LIKE ?";
            }
            $sql .= " ORDER BY id DESC";
            
            $stmt = $this->prepare($sql);
            if (!empty($keyword)) {
                $search = "%{$keyword}%";
                $stmt->bind_param("ss", $search, $search);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $customer = new Customer(
                    $row["fullname"],
                    $row["phone"],
                    $row["email"],
                    $row["address"],
                    $row["note"]
                );
                $customer->id = $row["id"];
                $list[] = $customer;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    public function findById(int $id): ?Customer
    {
        try {
            $sql = "SELECT * FROM customers WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $customer = new Customer(
                    $row["fullname"],
                    $row["phone"],
                    $row["email"],
                    $row["address"],
                    $row["note"]
                );
                $customer->id = $row["id"];
                return $customer;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function insert(Customer $customer): bool
    {
        try {
            $sql = "INSERT INTO customers(fullname, phone, email, address, note)
                    VALUES(?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "sssss",
                $customer->fullname,
                $customer->phone,
                $customer->email,
                $customer->address,
                $customer->note
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(Customer $customer): bool
    {
        try {
            $sql = "UPDATE customers
                    SET fullname=?, phone=?, email=?, address=?, note=?
                    WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "sssssi",
                $customer->fullname,
                $customer->phone,
                $customer->email,
                $customer->address,
                $customer->note,
                $customer->id
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM customers WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPage(int $limit, int $offset, string $keyword = ""): array
    {
        $customers = [];
        try {
            $sql = "SELECT * FROM customers";
            if ($keyword != "") {
                $sql .= " WHERE fullname LIKE ?";
            }
            $sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
            
            $stmt = $this->prepare($sql);
            if ($keyword != "") {
                $searchTerm = "%$keyword%";
                $stmt->bind_param("sii", $searchTerm, $limit, $offset);
            } else {
                $stmt->bind_param("ii", $limit, $offset);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $customer = new Customer(
                    $row["fullname"],
                    $row["email"],
                    $row["password"],
                    $row["phone"],
                    $row["address"],
                    $row["status"]
                );
                $customer->id = $row["id"];
                $customer->createdAt = $row["created_at"];
                $customer->updatedAt = $row["updated_at"];
                $customers[] = $customer;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $customers;
    }
}
