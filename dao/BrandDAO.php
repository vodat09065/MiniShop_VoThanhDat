<?php
namespace DAO;

use Models\Brand;
use \Exception;
class BrandDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(string $keyword = ""): array
    {
        $list = [];
        try {
            $sql = "SELECT * FROM brands";
            if (!empty($keyword)) {
                $sql .= " WHERE brandname LIKE ? OR slug LIKE ?";
            }
            $sql .= " ORDER BY brandname";
            
            $stmt = $this->prepare($sql);
            if (!empty($keyword)) {
                $search = "%{$keyword}%";
                $stmt->bind_param("ss", $search, $search);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $brand = new Brand(
                    $row["brandname"],
                    $row["slug"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $brand->id = $row["id"];
                $brand->createdAt = $row["created_at"];
                $brand->updatedAt = $row["updated_at"];
                $list[] = $brand;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    public function findById(int $id): ?Brand
    {
        try {
            $sql = "SELECT * FROM brands WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $brand = new Brand(
                    $row["brandname"],
                    $row["slug"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $brand->id = $row["id"];
                $brand->createdAt = $row["created_at"];
                $brand->updatedAt = $row["updated_at"];
                return $brand;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function insert(Brand $brand): bool
    {
        try {
            $sql = "INSERT INTO brands(brandname,slug,image,description,status)
                    VALUES(?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "ssssi",
                $brand->brandname,
                $brand->slug,
                $brand->image,
                $brand->description,
                $brand->status
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(Brand $brand): bool
    {
        try {
            $sql = "UPDATE brands
                    SET brandname=?, slug=?, image=?, description=?, status=?
                    WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "ssssii",
                $brand->brandname,
                $brand->slug,
                $brand->image,
                $brand->description,
                $brand->status,
                $brand->id
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM brands WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPage(int $limit, int $offset, string $keyword = ""): array
    {
        $brands = [];
        try {
            $sql = "SELECT * FROM brands";
            if ($keyword != "") {
                $sql .= " WHERE brandname LIKE ?";
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
                $brand = new Brand(
                    $row["brandname"],
                    $row["slug"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $brand->id = $row["id"];
                $brand->createdAt = $row["created_at"];
                $brand->updatedAt = $row["updated_at"];
                $brands[] = $brand;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $brands;
    }

    public function getByLimit(int $limit = 5): array
    {
        $brands = [];
        try {
            $sql = "SELECT * FROM brands ORDER BY id DESC LIMIT ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $brand = new Brand(
                    $row["brandname"],
                    $row["slug"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $brand->id = $row["id"];
                $brand->createdAt = $row["created_at"];
                $brand->updatedAt = $row["updated_at"];
                $brands[] = $brand;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $brands;
    }
}
