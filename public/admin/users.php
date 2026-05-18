<?php
require_once __DIR__ . '/../../includes/header.php';

if (!$is_admin) {
    header('Location: ../login.php');
    exit;
}

$userObj = new User();
$message = '';

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    if ($userObj->deleteUser($_GET['id'])) {
        $message = "Utilisateur supprimé.";
    }
}

$users = $userObj->getAllUsers();
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
        <h2>Gestion des Utilisateurs</h2>
        <?php if ($message): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        <td>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <a href="users.php?action=delete&id=<?php echo $u['id']; ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
