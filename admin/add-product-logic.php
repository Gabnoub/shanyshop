<?php
Require 'config/database.php';

if (isset($_POST['add_submit'])) {
    // get form data
    $category = filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $en_stock = filter_var($_POST['en_stock'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $material = filter_var($_POST['material'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $color = filter_var($_POST['color'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $size = filter_var($_POST['size'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description1 = filter_var($_POST['description1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bulletpoint1 = filter_var($_POST['bulletpoint1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bulletpoint2 = filter_var($_POST['bulletpoint2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bulletpoint3 = filter_var($_POST['bulletpoint3'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bulletpoint4 = filter_var($_POST['bulletpoint4'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description2 = filter_var($_POST['description2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $image1 = $_FILES['image1'];
    $image2 = $_FILES['image2'];
    $image3 = $_FILES['image3'];
    $image4 = $_FILES['image4'];
    $price = filter_var($_POST['price'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $discount = filter_var($_POST['discount'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // validate input
    if ($category === 'null') {
        $_SESSION['add'] = "category required"; 
    } elseif ($en_stock === 'null') {
        $_SESSION['add'] = "stock status required";
    } elseif (!$title) {
        $_SESSION['add'] = "title required";
    } elseif (!$description1) {
        $_SESSION['add'] = "description is required";
    } elseif (!$image1['name']) {
        $_SESSION['add'] = "first image is required";
    } elseif (!$price) {
        $_SESSION['add'] = "price is required";
    } else {
        // Finaler Preis berechnen
        if (isset($discount)) {
            $final_price = $price - $discount;
        } else {
            $final_price = $price;
        }
        $final_price = max($final_price, 0); // keine negativen Preise!
        
        // work on images
        $time = time(); // make each image name unique using timestamp
        $images = [$image1, $image2, $image3, $image4];
        // check if upload folder does not exists and create it
            $upload_folder = __DIR__ . '\\images\\';
            if (!is_dir($upload_folder)) {
                mkdir($upload_folder, 0755, true);
            }

        for ($i = 0; $i < 4; $i++) {
            if (isset($images[$i]) && !empty($images[$i]['name'])) {
                $images_name[$i] = $time . '-' . preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $images[$i]['name']);
                $images_tmp_name[$i] = $images[$i]['tmp_name'];
                $images_destination_path[$i] = __DIR__ . '\\images\\' . $images_name[$i];

                // make sure file is an image
                $allowed_files = ['png','jpg','jpeg','webp'];
                $extension = strtolower(pathinfo($images_name[$i], PATHINFO_EXTENSION));
                if ($extension) {
                    if (in_array($extension, $allowed_files)) {
                        //make sure image is not too large (max 1mb)
                        if ($images[$i]['size'] < 1000000) {
                            // upload image
                            move_uploaded_file($images_tmp_name[$i], $images_destination_path[$i]);
                        } else {
                            $_SESSION['add'] = "Image$i size too big. Should be less than 1mb";
                            header("location: addproduct.php");
                            die();

                        }
                    } else {
                        // not supported image format
                        $_SESSION['add'] = "File should be jpeg, jpg, png or webp";
                        header("location: addproduct.php");
                        die();
                    }
                }
                }
              
            
            
        }

    }
    // redirect back to add product page if there was any problem
   if (isset($_SESSION['add'])) {
    // pass form data back to add product page
    $_SESSION['add-data'] = $_POST;
    header("location: addproduct.php");
    die();
   } else {
    // insert new product into products table
    $insert_product_query = "INSERT INTO products (category, en_stock, title, material, color, size, description1, bulletpoint1, bulletpoint2, bulletpoint3, bulletpoint4, description2, image1, image2, image3, image4, price, discount, final_price) VALUES ('$category','$en_stock','$title','$material','$color','$size','$description1','$bulletpoint1','$bulletpoint2','$bulletpoint3','$bulletpoint4','$description2','$images_name[0]','$images_name[1]','$images_name[2]','$images_name[3]','$price','$discount','$final_price')";
    $insert_product_result = mysqli_query($connection, $insert_product_query);
    if (!mysqli_errno($connection)) {
        // redirect to add product page with success message
        $_SESSION['add-success'] = "Product registration successfull";
        header("location: index.php");
        die();
    } else {
    // redirect back to add product page if there was any problem
    header("location: addproduct.php");
    die();
    }
    }
   
} else {
    header("location: addproduct.php");
    die();
}


?>