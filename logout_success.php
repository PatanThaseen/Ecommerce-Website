<?php
session_start(); // Start the session

// Check if the logout message is set
$logout_message = isset($_SESSION['logout_message']) ? $_SESSION['logout_message'] : '';

// Unset the logout message after it is displayed
unset($_SESSION['logout_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 300px;
        }

        h1 {
            color: #28a745;
        }

        p {
            font-size: 16px;
            color: #333;
            margin-top: 20px;
        }

        .button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .go-home {
            background-color: #28a745;
        }

        .go-home:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Logout Successful</h1>
    <p><?= htmlspecialchars($logout_message); ?></p>
    <p>Thank you for visiting! You can either log back in or return to the homepage.</p>
    
    <a href="pages/login.php" class="button">Log In Again</a>
    <br>
    <br>
    <br>
    <a href="index.php" class="button go-home">Go to Homepage</a>
</div>

</body>
</html>
