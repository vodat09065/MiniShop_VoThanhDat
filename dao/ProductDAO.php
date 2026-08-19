<?php
namespace DAO;

use Models\Product;
use \Exception;
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

    public function insert(Product $product): int
    {
        try {
            $sql = "INSERT INTO products(category_id, brand_id, proname, slug, price, discount_price, quantity, image, description, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param(
                "iissddissi",
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
            if ($stmt->execute()) {
                return $this->conn->insert_id;
            }
            return 0;
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
                "iissddissii",
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

    // --- CÁC HÀM QUẢN LÝ ẢNH PHỤ (PRODUCT_IMAGES) ---
    public function insertImage(int $productId, string $image): bool
    {
        try {
            $sql = "INSERT INTO product_images(product_id, image) VALUES(?, ?)";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("is", $productId, $image);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getImagesByProductId(int $productId): array
    {
        $list = [];
        try {
            
            $sql = "SELECT * FROM product_images WHERE product_id=? ORDER BY id ASC";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $img = new ProductImage(
                    $row["product_id"],
                    $row["image"],
                    $row["sort_order"] ?? 0
                );
                $img->id = $row["id"];
                $img->createdAt = $row["created_at"];
                $list[] = $img;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $list;
    }

    public function deleteImage(int $id): bool
    {
        try {
            // Lấy tên ảnh trước để xóa file
            $sqlGet = "SELECT image FROM product_images WHERE id=?";
            $stmtGet = $this->prepare($sqlGet);
            $stmtGet->bind_param("i", $id);
            $stmtGet->execute();
            $res = $stmtGet->get_result();
            if ($row = $res->fetch_assoc()) {
                $fileName = $row["image"];
                $filePath = __DIR__ . "/../uploads/products/" . $fileName;
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
            }

            // Xóa record
            $sql = "DELETE FROM product_images WHERE id=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getPage(int $limit, int $offset, string $keyword = "", string $sort = ""): array
    {
        $products = [];
        try {
            $sql = "SELECT p.*, c.catename, b.brandname 
                    FROM products p
                    INNER JOIN categories c ON p.category_id = c.id
                    INNER JOIN brands b ON p.brand_id = b.id";
            
            if ($keyword != "") {
                $sql .= " WHERE p.proname LIKE ?";
            }
            
            $orderBy = "p.id DESC";
            if ($sort == "name_asc") $orderBy = "p.proname ASC";
            elseif ($sort == "name_desc") $orderBy = "p.proname DESC";
            elseif ($sort == "price_asc") $orderBy = "p.price ASC";
            elseif ($sort == "price_desc") $orderBy = "p.price DESC";

            $sql .= " ORDER BY $orderBy LIMIT ? OFFSET ?";
            
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
                $product->catename = $row["catename"];
                $product->brandname = $row["brandname"];
                $product->createdAt = $row["created_at"];
                $product->updatedAt = $row["updated_at"];
                $products[] = $product;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $products;
    }

    public function getDiscountProducts(int $limit = 8): array
    {
        $list = [];
        try {
            $sql = "SELECT p.*, c.catename AS cateName, b.brandname AS brandName 
                    FROM products p
                    INNER JOIN categories c ON p.category_id = c.id
                    INNER JOIN brands b ON p.brand_id = b.id
                    WHERE p.discount_price > 0
                    ORDER BY p.id DESC LIMIT ?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"], $row["brand_id"], $row["proname"], $row["slug"],
                    $row["price"], $row["discount_price"], $row["quantity"], $row["image"],
                    $row["description"], $row["status"]
                );
                $product->id = $row["id"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function getNewProducts(int $limit = 4): array
    {
        return $this->getLatest($limit);
    }

    public function getByCategory(string $slug): array
    {
        $list = [];
        try {
            $sql = "SELECT p.*, c.catename AS cateName, b.brandname AS brandName 
                    FROM products p
                    INNER JOIN categories c ON p.category_id = c.id
                    INNER JOIN brands b ON p.brand_id = b.id
                    WHERE c.slug = ?
                    ORDER BY p.id DESC";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"], $row["brand_id"], $row["proname"], $row["slug"],
                    $row["price"], $row["discount_price"], $row["quantity"], $row["image"],
                    $row["description"], $row["status"]
                );
                $product->id = $row["id"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function getByBrand(string $slug): array
    {
        $list = [];
        try {
            $sql = "SELECT p.*, c.catename AS cateName, b.brandname AS brandName 
                    FROM products p
                    INNER JOIN categories c ON p.category_id = c.id
                    INNER JOIN brands b ON p.brand_id = b.id
                    WHERE b.slug = ?
                    ORDER BY p.id DESC";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"], $row["brand_id"], $row["proname"], $row["slug"],
                    $row["price"], $row["discount_price"], $row["quantity"], $row["image"],
                    $row["description"], $row["status"]
                );
                $product->id = $row["id"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                $list[] = $product;
            }
        } catch (Exception $e) { throw $e; }
        return $list;
    }

    public function search(string $keyword): array
    {
        return $this->getAll($keyword);
    }
    
    public function getBySlug(string $slug): ?Product
    {
        try {
            $sql = "SELECT p.*, c.catename AS cateName, b.brandname AS brandName 
                    FROM products p
                    INNER JOIN categories c ON p.category_id = c.id
                    INNER JOIN brands b ON p.brand_id = b.id 
                    WHERE p.slug=?";
            $stmt = $this->prepare($sql);
            $stmt->bind_param("s", $slug);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["category_id"], $row["brand_id"], $row["proname"], $row["slug"],
                    $row["price"], $row["discount_price"], $row["quantity"], $row["image"],
                    $row["description"], $row["status"]
                );
                $product->id = $row["id"];
                $product->cateName = $row["cateName"];
                $product->brandName = $row["brandName"];
                return $product;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return null;
    }
}
