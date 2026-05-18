<?php
require_once __DIR__ . '/../includes/header.php';

$productObj = new Product();
$products = $productObj->getAllProducts();
?>

<section class="hero" style="text-align: center; padding: 3rem 1rem; background: var(--accent-color); color: white; border-radius: 8px; margin-bottom: 2rem;">
    <h1>Bienvenue sur SportShop</h1>
    <p>Votre destination pour le meilleur matériel de sport.</p>
</section>

<h2>Nos Produits</h2>
<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>Aucun produit disponible pour le moment.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <?php if ($product['image_url']): ?>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/300x200?text=Sport+Equipment" alt="Placeholder">
                <?php endif; ?>
                <div class="info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="category"><?php echo htmlspecialchars($product['category']); ?></p>
                    <p class="price"><?php echo htmlspecialchars($product['price']); ?> €</p>
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="button" style="display:inline-block; margin-top: 1rem; text-decoration:none; background: var(--secondary-color); color: white; padding: 0.5rem 1rem; border-radius: 4px;">Voir le produit</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
