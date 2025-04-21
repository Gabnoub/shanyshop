<?php
Include 'partials/header.php';

$sort = $_GET['sort'] ?? 'default';

switch ($sort) {
    case 'price_asc':
        $orderBy = 'final_price ASC';
        break;
    case 'price_desc':
        $orderBy = 'final_price DESC';
        break;
    case 'title_asc':
        $orderBy = 'title ASC';
        break;
    case 'title_desc':
        $orderBy = 'title DESC';
        break;
    default:
        $orderBy = 'id DESC';
}

// Kategorie-Filter prüfen
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $where = "WHERE category = $id";

} else {
    $where = "";
    // $category_name = "Tous les produits";
    // $category_description = "Découvrez notre sélection complète de bijoux élégants.";
}

// Produkte holen
$fetch_products_query = "SELECT * FROM products $where ORDER BY $orderBy";
$fetch_products_result = mysqli_query($connection, $fetch_products_query);

// Anzahl zählen
if (!empty($id)) {
    $stmt = $connection->prepare("SELECT COUNT(*) as count FROM products WHERE category = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'];
} else {
    $count = mysqli_num_rows($fetch_products_result);
}


if (!$fetch_products_result) {
    echo "No product available.";
exit;
}

?>
<!----========================================== Category Section =================================================---->
<section>
    <div class="home__category">
            <a style="color:black" href="index.php">Accueil/</a>
            <strong><?=  $shany_categories[$id ?? 4] ?></strong>
    </div>    
    <div class="category__description">
        <h2><?= $shany_categories[$id ?? 4] ?></h2>
        <p><?= $shany_categories_description[$id ?? 4] ?></p>
        <div class="cat_filter">
            <?php if ($count === 1): ?>
                <p class="num__products"><?= $count ?> produit</p>
            <?php else: ?>
                <p class="num__products"><?= $count ?> produits</p>    
            <?php endif; ?>
            <form class="form__category" method="GET" action="">
                <input type="hidden" name="id" value="<?= $id ?>">
                <label for="sort">Trier par:</label>
                <select name="sort" id="sort" onchange="this.form.submit()">
                    <option value="default" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'default') ? 'selected' : '' ?>>Standard</option>
                    <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Prix: faible à élévé</option>
                    <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Prix: élévé à faible</option>
                    <option value="title_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'title_asc') ? 'selected' : '' ?>>Titre A-Z</option>
                    <option value="title_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'title_desc') ? 'selected' : '' ?>>Titre Z-A</option>
                </select>
            </form>
        </div>
    </div>
    

    <div class="cat__products-container">
        <?php while($row = $fetch_products_result->fetch_assoc()): ?>
            <div class="cat__product-item">
                <a class="cat__pr_link" href="product.php?id=<?= $row['id'] ?>"><img src="admin/images/<?= htmlspecialchars($row['image1']) ?>">
                <p class="cat__pr__title"><?= htmlspecialchars($row['title']) ?></p>
                <?php if ($row['price'] !== $row['final_price']): ?>
                <p class="cat__pr__price"><del style="text-decoration: line-through;"><?= number_format($row['price'], 0, ',', '.') ?></del>  <strong><?= number_format($row['final_price'], 0, ',', '.') ?> CFA</strong></p>
                <button class="rabatt">- <?= round(100 - (($row['final_price'] * 100) / $row['price'])) ?> %</button>
                <?php else: ?>
                <p class="cat__pr__price"><strong><?= $row['price'] ?> CFA</strong></p>
                <?php endif; ?>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php
Include 'partials/footer.php';
?>