<?php
class Product
{
    public int $id;
    public int $categoryId;
    public int $brandId;
    public string $proname;
    public string $slug;
    public float $price;
    public float $discountPrice;
    public int $quantity;
    public ?string $image;
    public ?string $description;
    public int $status;
    public string $createdAt;
    public string $updatedAt;

    // Thuộc tính lấy từ bảng JOIN
    public string $cateName = "";
    public string $brandName = "";

    public function __construct(
        int $categoryId = 0,
        int $brandId = 0,
        string $proname = "",
        string $slug = "",
        float $price = 0,
        float $discountPrice = 0,
        int $quantity = 0,
        ?string $image = null,
        ?string $description = null,
        int $status = 1
    ) {
        $this->categoryId = $categoryId;
        $this->brandId = $brandId;
        $this->proname = $proname;
        $this->slug = $slug;
        $this->price = $price;
        $this->discountPrice = $discountPrice;
        $this->quantity = $quantity;
        $this->image = $image;
        $this->description = $description;
        $this->status = $status;
    }
}
