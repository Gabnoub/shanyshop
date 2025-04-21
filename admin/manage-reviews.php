<?php
// manage_reviews.php
include 'partials/header.php';

// Prüfung auf Admin-Rechte könnte hier eingebaut werden
// if (!isset($_SESSION['user_is_admin']) || $_SESSION['user_is_admin'] !== true) {
//     header("Location: " . ROOT_URL . "index.php");
//     exit;
// }

// Anzeige von Erfolgsmeldungen
// if (isset($_SESSION['review_success'])) {
//     echo '<div class="alert alert-success">' . $_SESSION['review_success'] . '</div>';
//     unset($_SESSION['review_success']);
// }

// Anzeige von Fehlermeldungen
// if (isset($_SESSION['review_error'])) {
//     echo '<div class="alert alert-danger">' . $_SESSION['review_error'] . '</div>';
//     unset($_SESSION['review_error']);
// }

// Hole alle Bewertungen aus der Datenbank
$query = "SELECT r.*, p.title as product_title 
          FROM product_ratings r 
          JOIN products p ON r.product_id = p.id 
          ORDER BY r.created_at DESC";
$result = $connection->query($query);
?>

<div class="container mt-4">
    <h1>Bewertungen verwalten</h1>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produkt</th>
                    <th>Benutzer</th>
                    <th>Bewertung</th>
                    <th>Kommentar</th>
                    <th>Status</th>
                    <th>Datum</th>
                    <th>Aktionen</th>
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
                                <button type="button" class="btn btn-sm btn-primary" 
                                        data-toggle="modal" 
                                        data-target="#editReviewModal<?= $review['id'] ?>">
                                    Bearbeiten
                                </button>
                                <a href="<?= ROOT_URL ?>rating_logic.php?delete_review_id=<?= $review['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Sind Sie sicher, dass Sie diese Bewertung löschen möchten?')">
                                    Löschen
                                </a>
                            </td>
                        </tr>
                        
                        <!-- Modal für die Bearbeitung dieser Bewertung -->
                        <div class="modal fade" id="editReviewModal<?= $review['id'] ?>" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    
                                    <form action="<?= ROOT_URL ?>edit-rating_logic.php" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                            
                                        </div>
                                      
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Keine Bewertungen gefunden</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../partials/footer.php'; ?>