<?php
Include 'partials/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Product-ID invalid.";
    exit;
}

$id = intval($_GET['id']);
$stmt = $connection->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}


?>
<!----========================================== Product Info Section ============================================---->  
<div class="product__container">
    
    <!-- Produktdetails -->
    <div class="product-section product-card" data-id="<?= $id ?>" data-title="<?= htmlspecialchars($product["title"]) ?>" data-price="<?= htmlspecialchars($product["final_price"]) ?>" data-image="<?= 'admin/images/' . htmlspecialchars($product['image1']) ?>"
    >
      <div class="product-image">
        <!-- <img class="main__prImage" src="images/1.jpg"> -->
        <?php if (!empty($product["image1"])): ?>
                <img class="main__prImage" src="admin/images/<?= htmlspecialchars($product["image1"]) ?>">
        <?php endif; ?>
        <div class="thumbnail">
          <?php for ($i = 1; $i <= 4; $i++): ?>
            <?php if (!empty($product["image$i"])): ?>
              <img class="tn__image" src="admin/images/<?= htmlspecialchars($product["image$i"]) ?>" style="cursor:pointer;">
            <?php endif; ?>
          <?php endfor; ?>
        </div>
      </div>
      <div class="product-info">
        <div class="first__infos">
            <!-- <h1>Boucles Anais</h1> -->
            <?php if (!empty($product["title"])): ?>
                <h1><?= htmlspecialchars($product["title"]) ?></h1>
            <?php endif; ?>
            <!-- <h4>5.000 Fcfa</h4> -->
            <?php if ($product['price'] !== $product['final_price']): ?>
              <p style="text-decoration: line-through;"><del><?= number_format($product['price'], 0, ',', '.') ?> CFA</del></p>
              <p><strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong></p>
            <?php else: ?>
              <p><strong><?= number_format($product['price'], 0, ',', '.') ?> CFA</strong></p>
            <?php endif; ?>

            <!-- <?php if (!empty($product["final_price"])): ?>
                <h4><?= htmlspecialchars($product["final_price"]) ?></h4>
            <?php endif; ?> -->
        </div>
        <div class="specific__infos">
            <ul>
                <!-- <li>Material:</li>
                <li>Couleur:</li>
                <li>Taille:</li> -->
                <?php if (!empty($product["material"])): ?>
                <li><?= htmlspecialchars($product["material"]) ?></li>
                <?php endif; ?>
                <?php if (!empty($product["color"])): ?>
                <li><?= htmlspecialchars($product["color"]) ?></li>
                <?php endif; ?>
                <?php if (!empty($product["size"])): ?>
                <li><?= htmlspecialchars($product["size"]) ?></li>
                <?php endif; ?>
                
            </ul>
        </div>
        
        <button class="add-to-cart-btn">Ajouter au panier</button>


        <div class="bullets">
            <?php if (!empty($product["description1"])): ?>
                <p class="bullets__start"><?= htmlspecialchars($product["description1"]) ?></p>
            <?php endif; ?>
            <div class="bullets__items">
                <ul>
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                      <?php if (!empty($product["bulletpoint$i"])): ?>
                        <li><?= htmlspecialchars($product["bulletpoint$i"]) ?></li> 
                      <?php endif; ?>
                    <?php endfor; ?>
                </ul>
            </div>
            <?php if (!empty($product["description2"])): ?>
                <p class="bullets__end"><?= htmlspecialchars($product["description2"]) ?></p>
            <?php endif; ?>
        </div>
        
      </div>
    </div>

    
    <!-- Ähnliche Produkte -->
    <div class="related-products">
        <h2>Ähnliche Produkte</h2>
        <div class="related-grid">
          <div class="related-item">
            <img src="" alt="Produkt 1">
            <p>Silberring mit Perle</p>
          </div>
          <div class="related-item">
            <img src="" alt="Produkt 2">
            <p>Roségold Armband</p>
          </div>
          <div class="related-item">
            <img src="" alt="Produkt 3">
            <p>Kettenanhänger Herz</p>
          </div>
          <div class="related-item">
            <img src="" alt="Produkt 3">
            <p>Bracelets Elois Herz</p>
          </div>
        </div>
      </div>

    <!-- Kommentare -->
    <div class="comments">
      <h2>Avis client</h2>
    </div>


  </div>
<!----========================================== END ============================================---->
<?php
Include 'partials/footer.php';
?>