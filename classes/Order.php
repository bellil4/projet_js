<?php

require_once __DIR__ . '/Database.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createOrder($user_id, $total_price, $items) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
            $stmt->execute([$user_id, $total_price]);
            $order_id = $this->db->lastInsertId();

            $stmtItem = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($items as $item) {
                $stmtItem->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
                
                // Update stock
                $stmtStock = $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $stmtStock->execute([$item['quantity'], $item['product_id']]);
            }

            $this->db->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getOrdersByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getAllOrders() {
        $stmt = $this->db->query("SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.created_at DESC");
        return $stmt->fetchAll();
    }

    public function getOrderItems($order_id) {
        $stmt = $this->db->prepare("SELECT order_items.*, products.name FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_id = ?");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll();
    }

    public function updateStatus($order_id, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $order_id]);
    }

    public function countOrders() {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM orders");
        $row = $stmt->fetch();
        return $row['count'];
    }
}
