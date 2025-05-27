<?php
session_start();
include '../includes/db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details
    try {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching product details: " . $e->getMessage());
    }

    // If the form is submitted, update the product
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = $_FILES['image'];

        // Check if an image is uploaded
        if ($image['error'] === UPLOAD_ERR_OK) {
            $image_tmp_name = $image['tmp_name'];
            $image_name = $image['name'];
            $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
            $new_image_name = uniqid() . '.' . $image_extension;
            $upload_dir = '../images/';
            $image_path = $upload_dir . $new_image_name;

            // Move the uploaded image to the desired directory
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                // Update product in the database with the new image
                $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $price, $description, $new_image_name, $product_id]);
            } else {
                $error_message = "Failed to upload the image.";
            }
        } else {
            // No new image, just update the other fields
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $price, $description, $product_id]);
        }

        $success_message = "Product updated successfully!";
    }
} else {
    header("Location: manage_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 16px;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        input[type="file"] {
            padding: 5px;
            font-size: 14px;
        }
        button {
            padding: 12px;
            font-size: 16px;
            color: white;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .success-message {
            color: green;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
        .error-message {
            color: red;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Product</h1>

    <?php if (isset($success_message)): ?>
        <p class="success-message"><?= $success_message; ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?= $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>

        <label for="price">Price</label>
        <input type="text" id="price" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($product['description']); ?></textarea>

        <label for="image">Product Image (optional)</label>
        <input type="file" id="image" name="image">

        <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="Product Image" width="100" style="margin-top: 10px;">

        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>
