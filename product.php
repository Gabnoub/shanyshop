<?php
Include 'partials/header.php';
$slug = $_GET['slug'] ?? '';

if ($slug) {
    $stmt = $connection->prepare("SELECT * FROM products WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if (!$product) {
        echo "Produkt nicht gefunden.";
        exit;
    }
} else {
    echo "Kein Produkt angegeben.";
    exit;
}
// Prepare and execute a query to fetch 4 random related products from the same category (excluding the current product)
$stmtRelated = $connection->prepare("SELECT id, title, image1, price, final_price, slug FROM products WHERE category = ? AND slug != ? ORDER BY RAND() LIMIT 4");
$stmtRelated->bind_param("is", $product['category'], $slug);
$stmtRelated->execute();
$relatedProducts = $stmtRelated->get_result();


?>
<!----========================================== Product Info Section ============================================---->
<div class="product__container">

    <!-- Produktdetails -->
    <div class="product-section product-card" data-id="<?= htmlspecialchars($product["id"]) ?>" data-title="<?= htmlspecialchars($product["title"]) ?>" data-price="<?= htmlspecialchars($product["final_price"]) ?>" data-image="<?= ROOT_URL . 'admin/images/' . htmlspecialchars($product['image1']) ?>" data-slug="<?= ($product['slug']) ?>">
      <div class="product-image">
        <!-- <img class="main__prImage" src="images/1.jpg"> -->
        <?php if (!empty($product["image1"])): ?>
                <img class="main__prImage" src="<?= ROOT_URL . 'admin/images/' . htmlspecialchars($product['image1']) ?>">
        <?php endif; ?>
        <div class="thumbnail">
          <?php for ($i = 1; $i <= 4; $i++): ?>
            <?php if (!empty($product["image$i"])): ?>
              <img class="tn__image" src="<?= ROOT_URL . 'admin/images/' . htmlspecialchars($product["image$i"]) ?>" style="cursor:pointer;">
            <?php endif; ?>
          <?php endfor; ?>
        </div>
      </div>
      <div class="product-info">
        <div class="first__infos">
            <div id="prd_title">
              <?php if (!empty($product["title"])): ?>
                  <p><?= htmlspecialchars($product["title"]) ?></p>
              <?php endif; ?>
              <!-- <div id="clone_review">clone review</div>  -->
            </div>
            <?php if ($product['price'] !== $product['final_price']): ?>
              <p style="text-decoration: line-through;"><del><?= number_format($product['price'], 0, ',', '.') ?> CFA</del></p>
              <p><strong><?= number_format($product['final_price'], 0, ',', '.') ?> CFA</strong></p>
              <p class="rabatt_pp"><strong>- <?= round(100 - (($product['final_price'] * 100) / $product['price'])) ?> %</strong></p>
            <?php else: ?>
              <p><strong><?= number_format($product['price'], 0, ',', '.') ?> CFA</strong></p>
            <?php endif; ?>

            <!-- <?php if (!empty($product["final_price"])): ?>
                <h4><?= htmlspecialchars($product["final_price"]) ?></h4>
            <?php endif; ?> -->
        </div>
        <div class="specific__infos">
            <ul>
                <?php if (!empty($product["material"])): ?>
                <li><strong>Matière: </strong><?= htmlspecialchars($product["material"]) ?></li>
                <?php endif; ?>
                <?php if (!empty($product["color"])): ?>
                <li><strong>Couleur: </strong><?= htmlspecialchars($product["color"]) ?></li>
                <?php endif; ?>
                <?php if (!empty($product["size"])): ?>
                <li><strong>Taille: </strong><?= htmlspecialchars($product["size"]) ?></li>
                <?php endif; ?>

            </ul>
        </div>

        <button style="cursor:pointer;" class="add-to-cart-btn">Ajouter au panier</button>


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

</div>
    <!-- Ähnliche Produkte -->
<div class="related-products">
    <h2>Vous aimerez également:</h2>
    <div class="related-grid">
      <?php while($relProduct = $relatedProducts->fetch_assoc()): ?>
        <div class="related-item">
          <a class="related_p" href="<?= ROOT_URL ?>products/<?= $relProduct['slug'] ?>">
            <img src="<?= ROOT_URL ?>admin/images/<?= htmlspecialchars($relProduct['image1']) ?>" alt="<?= htmlspecialchars($relProduct['title']) ?>">
          <p><?= htmlspecialchars($relProduct['title']) ?></p>
          <p >
            <?php if ($relProduct['price'] !== $relProduct['final_price']): ?>
              <del style="text-decoration: line-through;"><?= number_format($relProduct['price'], 0, ',', '.') ?></del>
              <strong><?= number_format($relProduct['final_price'], 0, ',', '.') ?> CFA</strong>
              <button class="rabatt">- <?= round(100 - (($relProduct['final_price'] * 100) / $relProduct['price'])) ?> %</button>
            <?php else: ?>
              <strong><?= number_format($relProduct['price'], 0, ',', '.') ?> CFA</strong>
            <?php endif; ?>
          </p>
          </a>
        </div>
      <?php endwhile; ?>
    </div>
</div>


<!----------------------------------------------------- Bewertungsbereich HTML ----------------------------------------------------------------->
<?php
// Am Ende deiner product.php, vor dem Footer-Include

// Durchschnittliche Bewertung und Anzahl der Bewertungen abrufen
$stmtRating = $connection->prepare("SELECT AVG(rating) as average, COUNT(*) as count FROM product_ratings WHERE product_id = ?");
$stmtRating->bind_param("i", $product['id']);
$stmtRating->execute();
$ratingResult = $stmtRating->get_result()->fetch_assoc();
$averageRating = round($ratingResult['average'], 1) ?: 0;
$ratingCount = $ratingResult['count'];

// Alle Bewertungen für dieses Produkt abrufen
$stmtComments = $connection->prepare("SELECT * FROM product_ratings WHERE product_id = ? ORDER BY created_at DESC");
$stmtComments->bind_param("i", $product['id']);
$stmtComments->execute();
$comments = $stmtComments->get_result();

// Fehlermeldungen aus der Session anzeigen
$ratingError = $_SESSION['review_error'] ?? "";
$ratingSuccess = $_SESSION['review_success'] ?? "";

// Session-Variablen löschen
unset($_SESSION['review_error']);
unset($_SESSION['review_success']);

// Formular-Daten aus der Session wiederherstellen
$formData = $_SESSION['review_data'] ?? [];
unset($_SESSION['review_data']);
?>

<!-- Bewertungsbereich HTML -->
<div class="comments-section">
    <h2>Avis Clients</h2>
    
    <!-- Durchschnittliche Bewertung anzeigen -->
    <div class="average-rating">
        <div class="rating-stars">
        <div id="rev_stars">    
          <?php for ($i = 1; $i <= 5; $i++): ?>
                  <?php if ($i <= $averageRating): ?>
                      <span class="star filled">★</span>
                  <?php elseif ($i - 0.5 <= $averageRating): ?>
                      <span class="star half-filled">★</span>
                  <?php else: ?>
                      <span class="star">☆</span>
                  <?php endif; ?>
          <?php endfor; ?>
        </div>
        <span class="rating-value"><?= $averageRating ?>/5</span>
        <span class="rating-count">(<?= $ratingCount ?> avis)</span>
        </div>
    </div>
    <h3 ID="review">Laissez votre avis</h3>  
    <?php if (isset($_GET['rating_success'])): ?>
            <div class="success-message">Merci pour votre avis!</div>
        <?php endif; ?>
        
        <?php if ($ratingError): ?>
            <div class="error-message"><?= $ratingError ?></div>
        <?php endif; ?>           
    <!-- Bewertungsformular -->
    <div class="rating-form">
        <form method="post" action="<?= ROOT_URL ?>admin/review-logic.php">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            
            <div class="form-group">
                <label for="user_name">Votre nom:</label>
                <input type="text" id="user_name" name="user_name" value="<?= $formData['user_name'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label>Votre évaluation:</label>
                <div class="star-rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" <?= ($formData['rating'] ?? 0) == $i ? 'checked' : '' ?> required>
                        <label for="star<?= $i ?>">☆</label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="comment">Votre commentaire (facultatif):</label>
                <textarea id="comment" name="comment" rows="4"><?= $formData['comment'] ?? '' ?></textarea>
            </div>
            
            <button type="submit" name="submit_rating" class="submit-rating-btn">Soumettre</button>
        </form>
    </div>
    
    <!-- Bewertungsliste -->
    <?php if ($ratingCount > 0): ?>
        <div class="comments-list">
            <h3>Avis (<?= $ratingCount ?>)</h3>
            
            <?php while ($comment = $comments->fetch_assoc()): ?>
                <div class="comment-item">
                    <div class="comment-header">
                        <span class="comment-author"><?= htmlspecialchars($comment['user_name']) ?></span>
                        <span class="comment-date"><?= date('d.m.Y', strtotime($comment['created_at'])) ?></span>
                    </div>
                    
                    <div class="comment-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $comment['rating']): ?>
                                <span class="star">★</span>
                            <?php else: ?>
                                <span class="star">☆</span>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    
                    <?php if (!empty($comment['comment'])): ?>
                        <div class="comment-text">
                            <?= nl2br(htmlspecialchars($comment['comment'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-comments">Aucun avis pour ce produit. Soyez le premier à donner votre avis!</p>
    <?php endif; ?>
</div>
<!----========================================== END ============================================---->
<?php
Include 'partials/footer.php';
?>