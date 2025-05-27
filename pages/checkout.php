<?php
session_start();
include '../includes/db.php'; // Include the database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];

    // Dummy payment logic
    $payment_status = "Pending"; // Update based on real payment gateway
    $order_id = uniqid(); // Generate a unique order ID

    try {
        // Insert order into the database
        $stmt = $conn->prepare("INSERT INTO orders (order_id, name, email, address, city, state, zip, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $name, $email, $address, $city, $state, $zip, $payment_status]);

        // Clear the cart (implement a proper cart logic as needed)
        unset($_SESSION['cart']);
        $success_message = "Your order has been placed successfully!";
    } catch (PDOException $e) {
        die("Error placing order: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .success-container {
            text-align: center;
            margin-top: 20px;
            background: white;
        }
        
        .success-image {
            max-width: 200px;
            margin: 20px auto;
        }
        
        .success-message {
            color: green;
            font-size: 1.5em;
        }
    </style>
</head>
<body>
    <header>
        <h1>Checkout</h1>
    </header>
    <main>
        <?php if (isset($success_message)): ?>
            <div class="success-container">
                <p class="success-message"><?= $success_message; ?></p>
                <p>Thank you for your order! Your order ID is <strong><?= htmlspecialchars($order_id); ?></strong>.</p>
                <!-- Display success image -->
                <img src="../images/success.jpg" alt="Order Successful" class="success-image">
                <a href="../index.php">Back to Home</a>
            </div>
        <?php else: ?>
            <form method="POST" action="checkout.php" class="checkout-form">
                <h2>Shipping Information</h2>
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>

                <label for="city">City</label>
                <input type="text" id="city" name="city" required>

                <label for="state">State</label>
                <input type="text" id="state" name="state" required>

                <label for="zip">Zip Code</label>
                <input type="text" id="zip" name="zip" required>

                <h2>Payment</h2>
                <p>For now, we are using a dummy payment system. Click the "Place Order" button to complete your order.</p>

                <button type="submit" class="place-order-button">Place Order</button>
            </form>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; <?= date('Y'); ?> Online Store. All rights reserved.</p>
    </footer>
</body>
</html>
