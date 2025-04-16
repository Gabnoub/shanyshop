<?php
require_once 'config/database.php';

if (isset($_POST['edit_submit']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $en_stock = $_POST['en_stock'];
    $title = $_POST['title'];
    $material = $_POST['material'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $description1 = $_POST['description1'];
    $bulletpoint1 = $_POST['bulletpoint1'];
    $bulletpoint2 = $_POST['bulletpoint2'];
    $bulletpoint3 = $_POST['bulletpoint3'];
    $bulletpoint4 = $_POST['bulletpoint4'];
    $description2 = $_POST['description2'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];

    // Validierung
    // if ($category === 'null')  {
    //     $_SESSION['edit'] = "Category required";
    //     header("Location: edit-product.php?id=" . urlencode($id));
    //     exit;
    // }
    // elseif ($en_stock === 'null') {
    //     $_SESSION['edit'] = "Stock status required";
    //     header("Location: edit-product.php?id=" . urlencode($id));
    //     exit;
    // } else {


        // validate input
    if ($category === 'null') {
        $_SESSION['edit'] = "category required"; 
    } elseif ($en_stock === 'null') {
        $_SESSION['edit'] = "stock status required";
    } elseif (!$title) {
        $_SESSION['edit'] = "title required";
    } elseif (!$description1) {
        $_SESSION['edit'] = "description is required";
    } elseif (!$price) {
        $_SESSION['edit'] = "price is required";
    } else {
    
    // Finaler Preis berechnen
    if (isset($discount)) {
        $final_price = $price - $discount;
    } else {
        $final_price = $price;
    }
    $final_price = max($final_price, 0); // keine negativen Preise!
    
    
    // Wenn neue Datei hochgeladen wurde
    $image1 = $_FILES['image1'];
    $image2 = $_FILES['image2'];
    $image3 = $_FILES['image3'];
    $image4 = $_FILES['image4'];
    $images = [$image1, $image2, $image3, $image4];

    // aktueller Bildname aus der DB
    $cur_image1 = $_POST['current_image1']; 
    $cur_image2 = $_POST['current_image2']; 
    $cur_image3 = $_POST['current_image3']; 
    $cur_image4 = $_POST['current_image4']; 
    $cur_images = [$cur_image1, $cur_image2, $cur_image3, $cur_image4];


    for ($i = 0; $i < 4; $i++) {
        if (isset($images[$i]) && !empty($images[$i]['name'])) {
            $extension = strtolower(pathinfo($images[$i]['name'], PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($extension, $allowed_exts) && $images[$i]['size'] < 1000000) {
                $new_image_names[$i] = time() . '-' . preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $images[$i]['name']);
                move_uploaded_file($images[$i]['tmp_name'], __DIR__ . '\\images\\' . $new_image_names[$i]);
                // altes Bild löschen (wenn vorhanden)
                if (!empty($cur_images[$i]) && file_exists(__DIR__ . '\\images\\' . $cur_images[$i])) {
                    unlink(__DIR__ . '\\images\\' . $cur_images[$i]);
                }
                $cur_images[$i] = $new_image_names[$i] ?? $cur_images[$i];
            }
        }
    }

    // UPDATE Text & images
    $sql = "UPDATE products SET
    category = ?, en_stock = ?, title = ?, material = ?, color = ?, size = ?,
    description1 = ?, bulletpoint1 = ?, bulletpoint2 = ?, bulletpoint3 = ?, bulletpoint4 = ?, description2 = ?,
    image1 = ?, image2 = ?, image3 = ?, image4 = ?, price = ?, discount = ?, final_price = ?
    WHERE id = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iissssssssssssssiiii",
    $category, $en_stock, $title, $material, $color, $size,
    $description1, $bulletpoint1, $bulletpoint2, $bulletpoint3, $bulletpoint4, $description2,
    $cur_images[0], 
    $cur_images[1], 
    $cur_images[2], 
    $cur_images[3], 
    $price, $discount, $final_price,
    $id
    );

    if ($stmt->execute()) {
        $_SESSION['add-success'] = "Product successfully updated.";
        header('Location: index.php');
    } else {
        $_SESSION['edit'] = "Error occured while updating product.";
        header("Location: edit-product.php?id=" . urlencode($id));
    }
    exit;

    }
    
    if (isset($_SESSION['edit'])) {
        // pass form data back to add product page
        header("Location: edit-product.php?id=" . urlencode($id));
        die();
    }

}

?>
