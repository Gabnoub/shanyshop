<?php
require 'config/database.php';

if (isset($_POST['shop_submit'])) {
    // Sanitize Inputs
    $promo = filter_var($_POST['promo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dec_title = filter_var($_POST['decouvrir_title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dec_text = filter_var($_POST['decouvrir_text'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $image_car_1 = $_FILES['image_car_1'];
    $image_car_2 = $_FILES['image_car_2'];
    $image_car_3 = $_FILES['image_car_3'];
    $image_lif_1 = $_FILES['image_lif_1'];
    $image_lif_2 = $_FILES['image_lif_2'];
    $image_lif_3 = $_FILES['image_lif_3'];
    $image_story = $_FILES['image_story'];
    $id = intval(1);

    // check inputs
    if (empty($promo)) {
        $_SESSION['shop'] = "promotion text is required"; 
    } elseif (empty($dec_title)) {
        $_SESSION['shop'] = "Decouvrir title is required"; 
    } elseif (empty($dec_text)) {
        $_SESSION['shop'] = "Decouvrir text is required";         
    } else {


    // Bilder
    // $time = time();
    $images = [$_FILES['image_car_1'], $_FILES['image_car_2'], $_FILES['image_car_3'], $_FILES['image_lif_1'],$_FILES['image_lif_2'], $_FILES['image_lif_3'], $_FILES['image_story']];
    // $image_names[] = '';

    // Previous images from form
    $cur_images = [
        $_POST['current_image_car_1'], 
        $_POST['current_image_car_2'], 
        $_POST['current_image_car_3'], 
        $_POST['current_image_lif_1'],
        $_POST['current_image_lif_2'],  
        $_POST['current_image_lif_3'],  
        $_POST['current_image_story']
    ];


    $upload_folder = __DIR__ . '/images/';
    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0755, true);
    }
    
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
    
    for ($i = 0; $i < 7; $i++) {
        if (!empty($images[$i]['name'])) {
            $extension = strtolower(pathinfo($images[$i]['name'], PATHINFO_EXTENSION));
            $size = $images[$i]['size'];
    
            if (!in_array($extension, $allowed_exts)) {
                $_SESSION['shop'] = "Image " . ($i + 1) . " has an unsupported format. Only jpg, jpeg, png, webp allowed.";
                header("Location: edit-shop.php");
                exit;
            }
    
            if ($size > 1000000) {
                $_SESSION['shop'] = "Image " . ($i + 1) . " is too large. Max allowed size is 1MB.";
                header("Location: edit-shop.php");
                exit;
            }
    
            // If valid, proceed with upload
            $new_image_name = time() . '-' . preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $images[$i]['name']);
            move_uploaded_file($images[$i]['tmp_name'], $upload_folder . $new_image_name);
    
            // Remove old image if exists
            if (!empty($cur_images[$i]) && file_exists($upload_folder . $cur_images[$i])) {
                unlink($upload_folder . $cur_images[$i]);
            }
    
            $cur_images[$i] = $new_image_name;
        }
    }
    
    
        // Prepare SQL Update
        $sql = "UPDATE shop_infos SET
            promo = ?, decouvrir_title = ?, decouvrir_text = ?, image_car_1 = ?, image_car_2 = ?, image_car_3 = ?, image_lif_1 = ?, image_lif_2 = ?,
            image_lif_3 = ?, image_story = ? WHERE id = ?";
        
        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            $_SESSION['shop'] = "SQL Error: " . $connection->error;
            header("Location: edit-shop.php");
            exit;
        }
        $stmt->bind_param("ssssssssssi",
            $promo, $dec_title, $dec_text, $cur_images[0], $cur_images[1], $cur_images[2], $cur_images[3], $cur_images[4],
            $cur_images[5], $cur_images[6],
            $id
        );
    

        if ($stmt->execute()) {
            $_SESSION['shop'] = "Shop infos successfully updated.";
            header('Location: edit-shop.php');
        } else {
            $_SESSION['shop'] = "Error occurred while updating shop.";
            header("Location: edit-shop.php");
        }
        exit;
    }
    if (isset($_SESSION['shop'])) {
        header("Location: edit-shop.php");
        exit;
    }
}

?>