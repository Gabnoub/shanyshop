<?php
// manage_reviews.php
include 'partials/header.php';


// Hole alle Bewertungen aus der Datenbank
$query = "SELECT r.*, p.title as product_title 
          FROM product_ratings r 
          JOIN products p ON r.product_id = p.id 
          ORDER BY r.created_at DESC";
$result = $connection->query($query);
?>

<a href="index.php" >
        <button style="margin: 1rem;" type="button" class="sub__btn cancel"><i class="uil uil-arrow-left"></i></button>
</a>

<div class="container mt-4">
    <h2>Gestion des avis</h2>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produit</th>
                    <th>Client</th>
                    <th>Avis</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($review = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $review['id'] ?></td>
                            <td><?= htmlspecialchars($review['product_title']) ?></td>
                            <td><?= htmlspecialchars($review['user_name']) ?></td>
                            <td>
                                <?php 
                                    // Sterne-Darstellung
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $review['rating']) {
                                            echo '★'; // voller Stern
                                        } else {
                                            echo '☆'; // leerer Stern
                                        }
                                    }
                                ?>
                            </td>
                            <td><?= htmlspecialchars($review['comment']) ?></td>
                            
                            <td><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></td>
                            <td>
                                <a href="edit-rating-logic.php?delete_review_id=<?= $review['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Sur de vouloir supprimer l\'avis?')">
                                    Löschen
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Aucun avis trouvé</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../partials/footer.php'; ?>