<?php
require_once __DIR__ . "/BaseDAO.php";
require_once __DIR__ . "/../models/Product.php";

class ProductDAO extends BaseDAO
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(string $keyword = ""): array
    {
        $list = [];
        try {
            $sql = "SELECT p.*, c.catename AS cateName, b.brandname AS brandName 
                    FROM products p
                    INNER JOIN categories c ON p.category_id = c.id
                    INNER JOIN brands b ON p.brand_id = b.id";
            if (!empty($keyword)) {
                $sql .= " WHERE p.proname LIKE ? OR c.catename LIKE ? OR b.brandname LIKE ?";
            }
            $sql .= " ORDER BY p.id DESC";
            
            $stmt = $this->prepare($sql);
            if (!empty($keyword)) {
                $search = "%{$keyword}%";
                $stmt->bind_param("sss", $search, $search, $search);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    public function getLatest(int $limit): array { 
        $list = []; 
        try { 
            $sql = "SELECT p.*, c.catename AS cateName, b.brandname AS brandName 
                    FROM products p
                    INNER JOIN categories c ON p.category_id = c.id
                    INNER JOIN brands b ON p.brand_id = b.id 
                    ORDER BY p.id DESC LIMIT ?"; 
            $stmt = $this->prepare($sql); 
            $stmt->bind_param("i", $limit); 
            $stmt->execute(); 
            $result = $stmt->get_result(); 
            while ($row = $result->fetch_assoc()) { 
                $product = new Product(
                    $row["category_id"], 
                    $row["brand_id"], 
                    $row["proname"], 
                    $row["slug"], 
                    $row["price"], 
                    $row["discount_price"], 
                    $row["quantity"], 
                    $row["image"], 
                    $row["description"], 
                    $row["status"]
                ); 
                $product->id = $row["id"]; 
                $product->createdAt = $row["created_at"]; 
                $product->updatedAt = $row["updated_at"]; 
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product; 
            } 
        } catch (Exception $e) { 
            throw $e; 
        } 
        return $list; 
    }

    public function findById(int $id): ?Product
    {
        try {
            $sql = "SELECT p.*, c.catename AS cateName, b.brandname AS brandName 
                    FROM products p
                    INNER JOIN categories c ON p.category_id = c.id
                    INNER JOIN brands b ON p.brand_id = b.id 
                    WHERE p.id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"],
                    $row["brand_id"],
                    $row["proname"],
                    $row["slug"],
                    $row["price"],
                    $row["discount_price"],
                    $row["quantity"],
                    $row["image"],
                    $row["description"],
                    $row["status"]
                );
                $product->id = $row["id"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                return $product;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }

    public function insert(Product $product): bool
    {
        try {
            $sql = "INSERT INTO products(category_id, brand_id, proname, slug, price, discount_price, quantity, image, description, status)
                    VALUES(?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iisssddisi",
                $product->categoryId,
                $product->brandId,
                $product->proname,
                $product->slug,
                $product->price,
                $product->discountPrice,
                $product->quantity,
                $product->image,
                $product->description,
                $product->status
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(Product $product): bool
    {
        try {
            $sql = "UPDATE products
                    SET category_id=?, brand_id=?, proname=?, slug=?, price=?, discount_price=?, quantity=?, image=?, description=?, status=?
                    WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iisssddisii",
                $product->categoryId,
                $product->brandId,
                $product->proname,
                $product->slug,
                $product->price,
                $product->discountPrice,
                $product->quantity,
                $product->image,
                $product->description,
                $product->status,
                $product->id
            );
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM products WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
