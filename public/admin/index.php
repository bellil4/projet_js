<?php
require_once __DIR__ . '/../../includes/header.php';

if (!$is_admin) {
    header('Location: ../login.php');
    exit;
}

$userObj = new User();
$productObj = new Product();
$orderObj = new Order();

$total_users = $userObj->countUsers();
$total_products = $productObj->countProducts();
$total_orders = $orderObj->countOrders();

// In a real app, we might calculate total revenue here too
$stmt = Database::getInstance()->getConnection()->query("SELECT SUM(total_price) as revenue FROM orders WHERE status = 'completed' OR status = 'pending'");
$revenue = $stmt->fetch()['revenue'] ?? 0;
?>

<div class="admin-container">
    <aside class="admin-sidebar">
        <h3>Administration</h3>
        <ul>
            <li><a href="index.php">Tableau de bord</a></li>
            <li><a href="products.php">Gérer les Produits</a></li>
            <li><a href="users.php">Gérer les Utilisateurs</a></li>
            <li><a href="orders.php">Gérer les Commandes</a></li>
        </ul>
    </aside>

    <main class="admin-content" style="margin: 0; padding: 0;">
        <h2>Tableau de bord</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h4>Utilisateurs</h4>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="stat-card">
                <h4>Produits</h4>
                <p><?php echo $total_products; ?></p>
            </div>
            <div class="stat-card">
                <h4>Commandes</h4>
                <p><?php echo $total_orders; ?></p>
            </div>
            <div class="stat-card">
                <h4>Chiffre d'Affaires</h4>
                <p><?php echo number_format($revenue, 2); ?> €</p>
            </div>
        </div>

        <h3>Actions Rapides</h3>
        <p><a href="products.php?action=add" class="button" style="text-decoration:none;">Ajouter un Produit</a></p>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
