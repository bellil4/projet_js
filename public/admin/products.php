<?php
require_once __DIR__ . '/../../includes/header.php';

if (!$is_admin) {
    header('Location: ../login.php');
    exit;
}

$productObj = new Product();
$action = $_GET['action'] ?? 'list';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $image_url = $_POST['existing_image'] ?? '';

    // Handle Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = __DIR__ . '/../uploads/';
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = '/uploads/' . $file_name;
        }
    }

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        if ($productObj->updateProduct($_POST['id'], $name, $description, $price, $stock, $image_url, $category)) {
            $message = "Produit mis à jour avec succès.";
        }
    } else {
        // Add
        if ($productObj->addProduct($name, $description, $price, $stock, $image_url, $category)) {
            $message = "Produit ajouté avec succès.";
        }
    }
    $action = 'list';
}

if ($action === 'delete' && isset($_GET['id'])) {
    if ($productObj->deleteProduct($_GET['id'])) {
        $message = "Produit supprimé.";
    }
    $action = 'list';
}

$products = $productObj->getAllProducts();
$edit_product = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $edit_product = $productObj->getProductById($_GET['id']);
}
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
        <h2>Gestion des Produits</h2>
        <?php if ($message): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($action === 'add' || $action === 'edit'): ?>
            <h3><?php echo $action === 'add' ? 'Ajouter' : 'Modifier'; ?> un Produit</h3>
            <form action="products.php" method="POST" enctype="multipart/form-data" class="form-container" style="margin: 1rem 0; max-width: 100%;">
                <input type="hidden" name="id" value="<?php echo $edit_product['id'] ?? ''; ?>">
                <input type="hidden" name="existing_image" value="<?php echo $edit_product['image_url'] ?? ''; ?>">
                
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="name" value="<?php echo $edit_product['name'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"><?php echo $edit_product['description'] ?? ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Prix (€)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $edit_product['price'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" value="<?php echo $edit_product['stock'] ?? '0'; ?>" required>
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <input type="text" name="category" value="<?php echo $edit_product['category'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label>Image (Upload obligatoire pour nouveau produit)</label>
                    <input type="file" name="image" <?php echo $action === 'add' ? 'required' : ''; ?>>
                    <?php if (isset($edit_product['image_url'])): ?>
                        <p>Image actuelle: <?php echo $edit_product['image_url']; ?></p>
                    <?php endif; ?>
                </div>
                <button type="submit">Enregistrer</button>
                <a href="products.php" class="button" style="background: gray; text-decoration: none;">Annuler</a>
            </form>
        <?php else: ?>
            <p><a href="products.php?action=add" class="button" style="text-decoration:none; margin-bottom: 1rem; display: inline-block;">Ajouter un Produit</a></p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><?php echo $p['price']; ?> €</td>
                            <td><?php echo $p['stock']; ?></td>
                            <td>
                                <a href="products.php?action=edit&id=<?php echo $p['id']; ?>">Modifier</a> | 
                                <a href="products.php?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
