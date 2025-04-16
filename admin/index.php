<?php
Include 'partials/header.php';

// Abfrage
$fetch_products_query = "SELECT id, title, category, en_stock, image1 FROM products";
$fetch_products_result = mysqli_query($connection, $fetch_products_query);




?>
<!----==========================================  Dashboard Section ============================================---->
<section class="dashboard">
    <div class="adBtn">
        <button class="ap"><a href="addproduct.php">Ajouter un produit</a></button>
        
        <?php if (!empty($_SESSION['add-success'])): ?>
            <div class="alert success">
                <h3>
                    <?= $_SESSION['add-success'];
                    unset($_SESSION['add-success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <?php if (isset($_SESSION['delete-success'])): ?>
        <div class="alert success"><?= $_SESSION['delete-success']; unset($_SESSION['delete-success']); ?></div>
        <?php elseif (isset($_SESSION['delete-error'])): ?>
        <div class="alert error"><?= $_SESSION['delete-error']; unset($_SESSION['delete-error']); ?></div>
        <?php endif; ?>

        <button class="lo"><a href="logout.php">Logout</a></button>
    </div>
    <div class="gestion">
        <h2>Gestion des produits</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>  
                    <th>Titre</th>
                    <th>Categorie</th>
                    <th>En stock</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $fetch_products_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td style="display:flex;">
                        <img src="images/<?= htmlspecialchars($row['image1']) ?>" style="height:40px; width:40px; object-fit:cover; vertical-align:middle; margin-right:10px;">
                        <?= htmlspecialchars($row['title']) ?>
                    </td>
                    <td><?= $shany_categories[htmlspecialchars($row['category'])] ?></td>
                    <td><?= $shany_en_stock[htmlspecialchars($row['en_stock'])] ?></td>
                    <td>
                    <a href="edit-product.php?id=<?= $row['id'] ?>">
                        <button style="background-color:blue; color:white; cursor:pointer; padding:0.5rem 1rem; border-radius:0.3rem;">Modifier</button>
                    </a>
                    </td>
                    <td>
                    <a href="delete-product.php?id=<?= $row['id'] ?>" onclick="return confirm('Really want to delete this product?')">
                        <button style="background-color:red; color:white; cursor:pointer; padding:0.5rem 1rem; border-radius:0.3rem;">Supprimer</button>
                    </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
</section>

<?php
Include '../partials/footer.php';
?>