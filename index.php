<?php
Include 'partials/header.php';
?>
    <!--- Caroussel Images  -->
    <div class="caroussel">
        <div class="caroussel__container">
                <img class="caroussel__image active"  src="images/1.jpg">
                <img class="caroussel__image"  src="images/2.jpg">
                <img class="caroussel__image"  src="images/4.png">
        </div>
        <div class="progress-bars">
            <div class="progress-bar" onclick="currentSlide(0)"></div>
            <div class="progress-bar" onclick="currentSlide(1)"></div>
            <div class="progress-bar" onclick="currentSlide(2)"></div>
        </div>
        <a class="call__to-action" href="">DÉCOUVRIR</a>
    </div>

<!----==========================================  New Products Section ============================================---->
<section class="new__products">
    <div class="np__title">
        <h2>Nos Nouveautés</h2>
    </div>
    <div class="new__products-container">
        <div class="new__product-item icon_wrapper">
            <a class="pr_link" href=""><img src="images/1.jpg" class="pr_image"></a>
            <!-- <div class="icon-overlay"><i class="uil uil-heart"></i></div> -->
            <p class="pr__title">Collier en argent</p>
            <p class="pr__price">15.000 FCFA</p>
        </div>
        <div class="new__product-item">
            <a class="pr_link" href=""><img src="images/2.jpg" class="pr_image"></a>
            <p class="pr__title">Collier en argent</p>
            <p class="pr__price">10.000 FCFA</p>
        </div>
        <div class="new__product-item">
            <a class="pr_link" href=""><img src="images/1.jpg" class="pr_image"></a>
            <p class="pr__title">Collier en argent</p>
            <p class="pr__price">25.000 FCFA</p>
        </div>
        <div class="new__product-item">
            <a class="pr_link" href=""><img src="images/2.jpg" class="pr_image"></a>
            <p class="pr__title">Collier en argent</p>
            <p class="pr__price">25.000 FCFA</p>
        </div>
    </div>
</section>
<!----========================================== Lifestyle section ============================================---->
<section class="lifestyle">
    <h2>Révelez votre style</h2>    
        <div class="lifestyle__images">
            <img src="images/1.jpg" alt="Lifestyle Image" class="lifestyle__image">
            <img src="images/2.jpg" alt="Lifestyle Image" class="lifestyle__image">
            <img src="images/1.jpg" alt="Lifestyle Image" class="lifestyle__image">
        </div>
    </div>
</section>
<!----========================================== Beststellers section ============================================---->
<section class="best__sellers">
    <h2>Nos Best Sellers</h2>
    <div class="best__sellers-container">
        <div class="best__sellers-item">
            <a class="pr_link" href=""><img src="images/1.jpg" class="pr_image"></a>
            <p class="pr__title">Collier en argent</p>
            <p class="pr__price">15.000 FCFA</p>
        </div>
        <div class="best__sellers-item">
            <a class="pr_link" href=""><img src="images/2.jpg" class="pr_image"></a>
            <p class="pr__title">Collier en argent</p>
            <p class="pr__price">10.000 FCFA</p>
        </div>
        <div class="best__sellers-item">
            <a class="pr_link" href=""><img src="images/1.jpg" class="pr_image"></a>
            <p class="pr__title">Collier en argent</p>
            <p class="pr__price">25.000 FCFA</p>
        </div>
        <div class="best__sellers-item">
            <a class="pr_link" href=""><img src="images/2.jpg" class="pr_image"></a>
            <p class="pr__title">Collier en argent</p>
            <p class="pr__price">25.000 FCFA</p>
        </div>
    </div>
</section>


<!----============================================== About section ============================================----------->
<section class="about">
    <div class="about__container">
        <img class="about__image"  src="images/about.webp">
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
<section class="caterogies">
    <h2>Découvrez notre collection exclusive</h2>
    <!-- <p><strong>Révélez votre style unique</strong></p> -->
    <div class="categories__container">
            <button onclick="currentCat(0)" class="categories__item">Colliers</button>
            <button onclick="currentCat(1)" class="categories__item">Bracelets</button>
            <button onclick="currentCat(2)" class="categories__item">Boucles d'oreilles</button>
            <button onclick="currentCat(3)" class="categories__item">Accessoires</button>
        <div ID="pbc" class="progress-bar__cat"></div>
    </div>
    <div class="product-container" id="productContainer"></div>
    <button class="open__product">Explorer</button>
</section>
<!----========================================== End ============================================---->
<?php
Include 'partials/footer.php';
?>
<script src="JS/main.js" defer></script>