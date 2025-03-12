<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

// Get the customer ID from the session
$customer_id = $_SESSION['user']['id']; 

// Display message about order confirmation status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['payment_done']) && $_POST['payment_done'] == 'yes') {
        // Update the status of the order to "awaiting_confirmation"
        $sql = "UPDATE orders SET status='awaiting_confirmation' WHERE customer_id=$customer_id AND status='pending'";
        if ($conn->query($sql)) {
            $_SESSION['success'] = "Order is confirmed. Awaiting admin approval.";
        } else {
            $_SESSION['error'] = "Error updating payment status.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        header {
            background: #155724;
            color: white;
            padding: 15px;
            font-size: 24px;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
        }
        .message {
            font-size: 18px;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .shop-info {
            margin-top: 20px;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .shop-info h3 {
            margin-top: 0;
            color: #333;
        }
        .shop-info p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #155724;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .back-link:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>Order Confirmation Awaiting</header>
        
        <?php
        // Display success or error messages
        if (isset($_SESSION['success'])) {
            echo "<div class='message success'>" . $_SESSION['success'] . "</div>";

            // Display shop address
            echo "<div class='shop-info'>";
            echo "<h3>For Equipment Purchase, Contact:</h3>";
            echo "<p><strong>GOA SCORES Sports Hub</strong><br>";
            echo "Shop No. 12, Ground Floor, Sports Avenue Complex,<br>";
            echo "Near Fatorda Stadium, Margao, Goa – 403601<br>";
            echo "Contact: +91 98765 43210<br>";
            echo "Email: contact@goascores.com</p>";
            echo "</div>";

            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo "<div class='message error'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <a href="cart.php" class="back-link">Back to Cart</a>
    </div>
</body>
</html>
