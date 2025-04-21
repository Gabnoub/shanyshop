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

$catslug = $_GET['cat_slug'] ?? null;

if ($catslug && $catslug !== "tous-les-produits"){
    // set $id
    if ($catslug === "Bracelets") {
        $id = 0;
    } elseif ($catslug === "Boucles-d-oreilles") {
        $id = 1;
    } elseif ($catslug === "Colliers") {
        $id = 2;
    } elseif ($catslug === "Accessoires") {
        $id = 3;
    }
    // Verwende vorbereitete Anweisung für die Produktauswahl
    $stmt = $connection->prepare("SELECT * FROM products WHERE cat_slug = ? ORDER BY $orderBy");
    $stmt->bind_param("s", $catslug);
    $stmt->execute();
    $fetch_products_result = $stmt->get_result();

    // Verwende vorbereitete Anweisung für die Zählung
    $count_stmt = $connection->prepare("SELECT COUNT(*) as count FROM products WHERE cat_slug = ?");
    $count_stmt->bind_param("s", $catslug);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $count = $count_result->fetch_assoc()['count'];
} else {
    // Wenn kein cat_slug angegeben ist
    $fetch_products_query = "SELECT * FROM products ORDER BY $orderBy";
    $fetch_products_result = mysqli_query($connection, $fetch_products_query);
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
            <a style="color:black" href="<?= ROOT_URL ?>">Accueil</a>
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
                <a class="cat__pr_link" href="<?= ROOT_URL ?>products/<?= $row['slug'] ?>"><img src="<?= ROOT_URL ?>admin/images/<?= htmlspecialchars($row['image1']) ?>">
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