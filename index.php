<?php
Include 'partials/header.php';

// Last new products fetch - New products Section
$last_products_query = "SELECT * FROM products ORDER BY id DESC LIMIT 4";
$last_products_result = mysqli_query($connection, $last_products_query);

// fetch 4 products order by discount desc - Beststellers Section
$best_products_query = "SELECT * FROM products ORDER BY discount DESC LIMIT 4";
$best_products_result = mysqli_query($connection, $best_products_query);

// fetch first 4 products for all categories
$cat_products = [];


for ($cat = 0; $cat < 4; $cat++) {
  $stmt = $connection->prepare("SELECT * FROM products WHERE category = ? ORDER BY id ASC LIMIT 4");
  $stmt->bind_param("i", $cat);
  $stmt->execute();
  $result = $stmt->get_result();
  $cat_products[$cat] = [];

  while ($row = $result->fetch_assoc()) {
    $cat_products[$cat][] = [
      'id' => $row['id'],
      'slug' => $row['slug'],
      'catSlug' => $row['cat_slug'],
      'title' => $row['title'],
      'price' => number_format($row['price'], 0, ',', '.'),
      'finalprice' => number_format($row['final_price'], 0, ',', '.'),
      'image' => 'admin/images/' . $row['image1']
    ];
  }
}
echo "<script>const products = " . json_encode($cat_products) . ";</script>";

?>
    <!--- Caroussel Images  -->
    <div class="caroussel">
        <div class="caroussel__container">
                <img class="caroussel__image active"  src="<?= ROOT_URL ?>admin/images/<?= $caroussel_1 ?>">
                <img class="caroussel__image"  src="<?= ROOT_URL ?>admin/images/<?= $caroussel_2 ?>">
                <img class="caroussel__image"  src="<?= ROOT_URL ?>admin/images/<?= $caroussel_3 ?>">
        </div>
        <div class="progress-bars">
            <div class="progress-bar" onclick="currentSlide(0)"></div>
            <div class="progress-bar" onclick="currentSlide(1)"></div>
            <div class="progress-bar" onclick="currentSlide(2)"></div>
        </div>
        <a class="call__to-action" href="<?= ROOT_URL ?>categories/<?= $dec_url ?>">DÉCOUVRIR</a>
    </div>

<!----==========================================  New Products Section ============================================---->
<section class="new__products animation">
  <div class="np__title">
    <h2>Nos Nouveautés</h2>
  </div>
  <div class="new__products-container">
    <?php while ($product = mysqli_fetch_assoc($last_products_result)): ?>
      <div class="new__product-item icon_wrapper">
        <!-- <a class="pr_link" href="product.php?id=<?= $product['id'] ?>"> -->
        <a class="pr_link" href="<?= ROOT_URL ?>products/<?= $product['slug'] ?>">

          <img src="admin/images/<?= htmlspecialchars($product['image1']) ?>" class="pr_image">
        
        <p class="pr__title"><?= htmlspecialchars($product['title']) ?></p>
        <p class="pr__price">
          <?php if ($product['price'] !== $product['final_price']): ?>
            <del style="text-decoration: line-through;"><?= number_format($product['price'], 0, ',', '.') ?></del>
            <strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong>
            <button class="rabatt">- <?= round(100 - (($product['final_price'] * 100) / $product['price'])) ?> %</button>
          <?php else: ?>
            <strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong>
          <?php endif; ?>
        </p>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!----========================================== Lifestyle section ============================================---->
<section class="lifestyle animation">
    <h2>Affirmez votre élégance</h2>    
        <div class="lifestyle__images">
            <img src="<?= ROOT_URL ?>admin/images/<?= $lifestyle_1 ?>" class="lifestyle__image">
            <img src="<?= ROOT_URL ?>admin/images/<?= $lifestyle_2 ?>" class="lifestyle__image">
            <img src="<?= ROOT_URL ?>admin/images/<?= $lifestyle_3 ?>" class="lifestyle__image">
        </div>
    </div>
</section>
<!----========================================== Beststellers section ============================================---->
<section class="best__sellers animation">
    <h2>Nos Best Sellers</h2>
    <div class="best__sellers-container">
    <?php while ($product = mysqli_fetch_assoc($best_products_result)): ?>
      <div class="best__sellers-item">
        <!-- <a class="pr_link" href="product.php?id=<?= $product['id'] ?>"> -->
        <a class="pr_link" href="<?= ROOT_URL ?>products/<?= $product['slug'] ?>">
          <img src="admin/images/<?= htmlspecialchars($product['image1']) ?>" class="pr_image">
        <p class="pr__title"><?= htmlspecialchars($product['title']) ?></p>
        <p class="pr__price">
          <?php if ($product['price'] !== $product['final_price']): ?>
            <del style="text-decoration: line-through;"><?= number_format($product['price'], 0, ',', '.') ?></del>
            <strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong>
            <button class="rabatt">- <?= round(100 - (($product['final_price'] * 100) / $product['price'])) ?> %</button>
          <?php else: ?>
            <strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong>
          <?php endif; ?>
        </p>
        </a>
      </div>
    <?php endwhile; ?>
    </div>
</section>


<!----============================================== About section ============================================----------->
<section class="about animation">
    <div class="about__container">
        <img class="about__image"  src="<?= ROOT_URL ?>admin/images/<?= $story ?>">
        <article class="about__text">
            <!-- <h3>Élégance raffinée</h3> -->
            <!-- <h2>Inspirée par Hawaii</h2> -->
             <h2>Notre histoire</h2>
            <h5>Fondée en 2022, Shany s’est d’abord imposée comme une marque audacieuse dans l’univers des piercings avant d’élargir son offre aux bijoux. Animée par une mission essentielle, elle propose des créations uniques et de qualité, permettant à chacun d’affirmer son style avec originalité. ✨💎</h5>
            <!-- <a class=" " href="">En savoir plus</a> -->
        </article>
    </div>
</section>
<!----========================================== Categories section ============================================---->
<section class="caterogies animation">
    <h2>Découvrez notre collection exclusive</h2>
    <!-- <p><strong>Révélez votre style unique</strong></p> -->
    <div class="categories__container">
            <button onclick="currentCat(0)" class="categories__item"><?= $shany_categories[0] ?></button>
            <button onclick="currentCat(1)" class="categories__item"><?= $shany_categories[1] ?></button>
            <button onclick="currentCat(2)" class="categories__item"><?= $shany_categories[2] ?></button>
            <button onclick="currentCat(3)" class="categories__item"><?= $shany_categories[3] ?></button>
        <div ID="pbc" class="progress-bar__cat"></div>
    </div>
    <div class="product-container" id="productContainer"></div>
    <button id="exploreBtn" class="open__product">Explorer</button>
</section>
<!----========================================== End ============================================---->
<?php
Include 'partials/footer.php';
?>
<script src="<?= ROOT_URL ?>JS/main.js?v=<?php echo time(); ?>" defer></script>