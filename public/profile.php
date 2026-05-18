<?php
require_once __DIR__ . '/../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userObj = new User();
$user = $userObj->findById($_SESSION['user_id']);

$orderObj = new Order();
$user_orders = $orderObj->getOrdersByUserId($_SESSION['user_id']);
?>

<div class="profile-container">
    <h2>Mon Compte</h2>
    <div class="user-info" style="background: white; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
        <p><strong>Nom d'utilisateur:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Rôle:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        <p><strong>Membre depuis le:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
    </div>

    <h3>Mes Commandes</h3>
    <?php if (empty($user_orders)): ?>
        <p>Vous n'avez pas encore passé de commande.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($user_orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_price']); ?> €</td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td>
                             <ul>
                                <?php 
                                $items = $orderObj->getOrderItems($order['id']);
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
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
