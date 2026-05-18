<?php
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$productObj = new Product();
$product = $productObj->getProductById($_GET['id']);

if (!$product) {
    echo "<h2>Produit non trouvé.</h2>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy'])) {
    if (!$is_logged_in) {
        header('Location: login.php');
        exit;
    }

    $orderObj = new Order();
    $items = [
        [
            'product_id' => $product['id'],
            'quantity' => 1,
            'price' => $product['price']
        ]
    ];
    
    if ($product['stock'] > 0) {
        if ($orderObj->createOrder($_SESSION['user_id'], $product['price'], $items)) {
            $message = "Commande réussie !";
            // Refresh product to show updated stock
            $product = $productObj->getProductById($product['id']);
        } else {
            $message = "Erreur lors de la commande.";
        }
    } else {
        $message = "Produit en rupture de stock.";
    }
}
?>

<div class="product-detail" style="display: flex; gap: 2rem; background: white; padding: 2rem; border-radius: 8px;">
    <div class="image" style="flex: 1;">
        <?php if ($product['image_url']): ?>
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; border-radius: 8px;">
        <?php else: ?>
            <img src="https://via.placeholder.com/600x400?text=Sport+Equipment" alt="Placeholder" style="width: 100%; border-radius: 8px;">
        <?php endif; ?>
    </div>
    <div class="info" style="flex: 1;">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="category" style="color: #666; margin-bottom: 1rem;"><?php echo htmlspecialchars($product['category']); ?></p>
        <p class="price" style="font-size: 2rem; color: var(--primary-color); font-weight: bold; margin-bottom: 1rem;"><?php echo htmlspecialchars($product['price']); ?> €</p>
        <p class="description" style="margin-bottom: 2rem;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        <p class="stock" style="margin-bottom: 1rem;">Stock: <?php echo $product['stock']; ?></p>

        <?php if ($message): ?>
            <p style="color: green; font-weight: bold; margin-bottom: 1rem;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" name="buy" <?php echo ($product['stock'] <= 0) ? 'disabled style="background:gray;"' : ''; ?>>
                <?php echo ($product['stock'] > 0) ? 'Commander Maintenant' : 'Rupture de stock'; ?>
            </button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
