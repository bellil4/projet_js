<?php
require_once __DIR__ . '/../../includes/header.php';

if (!$is_admin) {
    header('Location: ../login.php');
    exit;
}

$orderObj = new Order();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    if ($orderObj->updateStatus($_POST['order_id'], $_POST['status'])) {
        $message = "Statut de la commande mis à jour.";
    }
}

$orders = $orderObj->getAllOrders();
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
        <h2>Gestion des Commandes</h2>
        <?php if ($message): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><?php echo $o['id']; ?></td>
                        <td><?php echo htmlspecialchars($o['username']); ?></td>
                        <td><?php echo $o['total_price']; ?> €</td>
                        <td><?php echo $o['created_at']; ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                <select name="status">
                                    <option value="pending" <?php echo $o['status'] === 'pending' ? 'selected' : ''; ?>>En attente</option>
                                    <option value="completed" <?php echo $o['status'] === 'completed' ? 'selected' : ''; ?>>Terminée</option>
                                    <option value="cancelled" <?php echo $o['status'] === 'cancelled' ? 'selected' : ''; ?>>Annulée</option>
                                </select>
                                <button type="submit" name="update_status" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">OK</button>
                            </form>
                        </td>
                        <td>
                            <ul>
                            <?php 
                            $items = $orderObj->getOrderItems($o['id']);
                            foreach ($items as $item):
                            ?>
                                <li><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</li>
                            <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
