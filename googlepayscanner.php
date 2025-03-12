<?php
session_start();
include 'db_connection.php';

if (isset($_GET['order_id'])) {
    $order_id = htmlspecialchars($_GET['order_id']); // Prevent XSS

    // Fetch amount from the database
    $query = "SELECT total_amount FROM orders WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if (!$order) {
        die("<h3>Error: Order ID not found in the database!</h3>");
    }

    $amount = $order['total_amount']; // Get the amount

} else {
    die("<h3>Error: Order ID not provided!</h3>");
}

// Close database connection
$stmt->close();
$conn->close();
    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Pay Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        .qr-code {
            margin: 20px 0;
        }
        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .done {
            background-color: #28a745;
            color: white;
        }
        .back {
            background-color: #dc3545;
            color: white;
        }
        button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Payment Method: Google Pay</h2>

        <div class="amount">
            <h3>Amount: $<?php echo $amount; ?></h3>
        <div class="qr-code">
            <img src="assets/9d2f4486-0485-4f3e-8f46-9d28cee049aa.jpg" alt="Google Pay QR Code" width="200">
        </div>
        <div class="buttons">
            <button class="done" onclick="window.location.href='corder.php?order_id=<?php echo $order_id; ?>'">Done</button>
            <button class="back" onclick="window.history.back()">Go Back</button>
        </div>
    </div>

</body>
</html>
