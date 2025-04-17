<?php
Require 'config/database.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css?v=<?php echo time(); ?>">
    <!--  Iconscout CDN-->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <!--  Google Fonts CDN-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Shany Shop</title>
    <script src="JS/script.js?v=<?php echo time(); ?>" defer></script>
</head>
<body>
<!----========================================== first Section - promo/NAV/Caroussel ============================================---->
<section class="first">
  <!------------------------------------------------------------ Promotion text -------------------------------------------------------------->
  <div class="promotion">Livraison offerte à Yaoundé dès 25.000 FCFA d'achat ✨</div>
  <!------------------------------------------------------------ Navigation Bar --------------------------------------------------------- -->
  <nav class="nav__container">
      <a class="nav__logo" href="<?= ROOT_URL ?>index.php">
          <!-- <img src="assets/logo.png" alt="Logo Shany Shop" class="nav__logo--img"> -->
              SHANY
      </a>
      <ul class="nav__links">
          <li class="nav__item"><a href="<?= ROOT_URL ?>category.php?id=2" class="nav__link">Colliers</a></li>
          <li class="nav__item"><a href="<?= ROOT_URL ?>category.php?id=1" class="nav__link">Boucles d'oreilles</a></li>
          <li class="nav__item"><a href="<?= ROOT_URL ?>category.php?id=0" class="nav__link">Bracelets</a></li>
          <li class="nav__item"><a href="<?= ROOT_URL ?>category.php?id=3" class="nav__link">Accessoires</a></li>
      </ul>
      <button id="menu__icon">☰</button>
      <div class="open__cart">
          <button class="active" id="cart__icon"><i class="uil uil-shopping-bag"></i></button>
          <button ID="cart__qty"></button>
      </div>
      <div ID="cartCont" class="cart__container">
          <button id="close__icon">✖</button>
          <div class="cit">
              <div ID="cp-items" class="cart__product-items">
                  <!-- <div class="cart__product-item" data-id="1">
                      <a class="cart__pr_link" href="product.php"><img src="images/1.jpg"></a>
                      <div class="cart__right">
                          <div class="cart_description">
                              <p class="cart__pr__title"><strong>Collier en argent</strong></p>
                              <p class="cart__pr__price">15.000 FCFA</p>
                          </div>
                          <div class="quantity">
                              <button ID="minus">-</button>
                              <button ID="qty">1</button>
                              <button ID="plus">+</button>
                          </div>
                      </div>
                  </div> -->
                  
              </div>
              <button id="commander">Commander via <i class="uil uil-whatsapp"></i> Whatsapp</button>
          </div>
      </div>
  </nav>
</section>