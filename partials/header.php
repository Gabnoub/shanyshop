<?php
Require 'config/database.php';

$sql = "SELECT * FROM shop_infos";
$stmt = $connection->prepare($sql);
$stmt->execute();
$shop_infos = $stmt->get_result()->fetch_assoc();
$promo = $shop_infos['promo'] ?? '';
$dec_title = $shop_infos['decouvrir_title'] ?? '';
$dec_text = $shop_infos['decouvrir_text'] ?? '';
$dec_url = $shop_infos['decouvrir_url'] ?? '';
$category_1 = html_entity_decode($shop_infos['category_1'], ENT_QUOTES, 'UTF-8') ?? '';
$category_2 = html_entity_decode($shop_infos['category_2'], ENT_QUOTES, 'UTF-8') ?? '';
$category_3 = html_entity_decode($shop_infos['category_3'], ENT_QUOTES, 'UTF-8') ?? '';
$category_4 = html_entity_decode($shop_infos['category_4'], ENT_QUOTES, 'UTF-8') ?? '';
$category_text_1 = $shop_infos['category_text_1'] ?? '';
$category_text_2 = $shop_infos['category_text_2'] ?? '';
$category_text_3 = $shop_infos['category_text_3'] ?? '';
$category_text_4 = $shop_infos['category_text_4'] ?? '';
$caroussel_1 = $shop_infos['image_car_1'] ?? '';
$caroussel_2 = $shop_infos['image_car_2'] ?? '';
$caroussel_3 = $shop_infos['image_car_3'] ?? '';
$lifestyle_1 = $shop_infos['image_lif_1'] ?? '';
$lifestyle_2 = $shop_infos['image_lif_2'] ?? '';
$lifestyle_3 = $shop_infos['image_lif_3'] ?? '';
$story = $shop_infos['image_story'] ?? '';
$text_story = $shop_infos['text_story'] ?? '';
$text_info_1 = $shop_infos['text_info_1'] ?? '';
$text_info_2 = $shop_infos['text_info_2'] ?? '';
$text_info_3 = $shop_infos['text_info_3'] ?? '';
$title_info_1 = $shop_infos['title_info_1'] ?? '';
$title_info_2 = $shop_infos['title_info_2'] ?? '';
$title_info_3 = $shop_infos['title_info_3'] ?? '';
$cat_slug[0] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_1) ?? '';
$cat_slug[1] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_2) ?? '';
$cat_slug[2] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_3) ?? '';
$cat_slug[3] =  preg_replace('/[^a-zA-Z0-9\-_]/', '-', $category_4) ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css?v=<?php echo time(); ?>">
    <!--  Iconscout CDN-->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <!--  Google Fonts CDN-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Shany Shop</title>
    <script src="<?= ROOT_URL ?>JS/script.js?v=<?php echo time(); ?>" defer></script>
</head>
<body>
<div id="app" data-root-url=<?= ROOT_URL ?>></div>
<!----========================================== first Section - promo/NAV/Caroussel ============================================---->
<section class="first">
  <!------------------------------------------------------------ Promotion text -------------------------------------------------------------->
  <div class="promotion"><?= $promo ?></div>
  <!------------------------------------------------------------ Navigation Bar --------------------------------------------------------- -->
  <nav class="nav__container">
      <a class="nav__logo" href="<?= ROOT_URL ?>">
          <!-- <img src="assets/logo.png" alt="Logo Shany Shop" class="nav__logo--img"> -->
              SHANY
      </a>
      <ul class="nav__links">
          <li class="nav__item"><a href="<?= ROOT_URL ?>categories/<?= $cat_slug[0] ?>" class="nav__link"><?= $category_1 ?></a></li>
          <li class="nav__item"><a href="<?= ROOT_URL ?>categories/<?= $cat_slug[1] ?>" class="nav__link"><?= $category_2 ?></a></li>
          <li class="nav__item"><a href="<?= ROOT_URL ?>categories/<?= $cat_slug[2] ?>" class="nav__link"><?= $category_3 ?></a></li>
          <li class="nav__item"><a href="<?= ROOT_URL ?>categories/<?= $cat_slug[3] ?>" class="nav__link"><?= $category_4 ?></a></li>
      </ul>
      <button id="menu__icon">☰</button>
      <div id="opcart" class="open__cart">
          <button class="active" id="cart__icon"><i class="uil uil-shopping-bag"></i></button>
          <button ID="cart__qty"></button>
      </div>
      <div ID="cartCont" class="cart__container">
          <button id="close__icon">✖</button>
          <div id="new__cart">
            <button id="cart__icon_new"><i class="uil uil-shopping-bag"></i></button>
            <button ID="cart__qty_new"></button>
          </div>
          <div class="cit">
            <div ID="cp-items" class="cart__product-items"></div>
          </div>
      </div>
  </nav>
</section>