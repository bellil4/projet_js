<?php

require_once __DIR__ . '/Database.php';

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllProducts() {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function addProduct($name, $description, $price, $stock, $image_url, $category) {
        $stmt = $this->db->prepare("INSERT INTO products (name, description, price, stock, image_url, category) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $price, $stock, $image_url, $category]);
    }

    public function updateProduct($id, $name, $description, $price, $stock, $image_url, $category) {
        $stmt = $this->db->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image_url = ?, category = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $price, $stock, $image_url, $category, $id]);
    }

    public function deleteProduct($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countProducts() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM products");
        $row = $stmt->fetch();
        return $row['count'];
    }
}
