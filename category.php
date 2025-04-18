<?php
Include 'partials/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Category-ID invalid.";
    exit;
}

$id = intval($_GET['id']);

// $stmt = $connection->prepare("SELECT * FROM products WHERE category = ?");
// $stmt->bind_param("i", $id);
// $stmt->execute();
// $product = $stmt->get_result()->fetch_assoc();

// if (!$product) {
//     echo "category not found.";
//     exit;
// }

// Abfrage
$fetch_products_query = "SELECT * FROM products WHERE category = $id";
$fetch_products_result = mysqli_query($connection, $fetch_products_query);

if (!$fetch_products_result) {
    echo "No product available.";
exit;
}
// SQL zum Zählen
$stmt = $connection->prepare("SELECT COUNT(*) as count FROM products WHERE category = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$count = $result->fetch_assoc()['count'];

?>
<!----========================================== Category Section =================================================---->
<section>
    <div class="home__category">
            <p>Accueil/<strong><?= $shany_categories[$id] ?></strong></p>
    </div>    
    <div class="category__description">
        <h2><?= $shany_categories[$id] ?></h2>
        <p><?= $shany_categories_description[$id] ?></p>
        <?php if ($count === 1): ?>
            <p class="num__products"><?= $count ?> produit</p>
        <?php else: ?>
            <p class="num__products"><?= $count ?> produits</p>    
        <?php endif; ?>
    </div>
    <div class="cat__products-container">
        <?php while($row = $fetch_products_result->fetch_assoc()): ?>
            <div class="cat__product-item">
                <a class="cat__pr_link" href="product.php?id=<?= $row['id'] ?>"><img src="admin/images/<?= htmlspecialchars($row['image1']) ?>"></a>
                <p class="cat__pr__title"><?= htmlspecialchars($row['title']) ?></p>
                <!-- <p class="cat__pr__price"><?= htmlspecialchars($row['final_price']) ?> CFA</p> -->
                <?php if ($row['price'] !== $row['final_price']): ?>
                <p class="cat__pr__price"><del style="text-decoration: line-through;"><?= number_format($row['price'], 0, ',', '.') ?></del>  <strong><?= number_format($row['final_price'], 0, ',', '.') ?> CFA</strong></p>
                <?php else: ?>
                <p class="cat__pr__price"><strong><?= $row['price'] ?> CFA</strong></p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php
Include 'partials/footer.php';
?>