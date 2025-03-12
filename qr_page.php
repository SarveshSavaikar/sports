<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$ground_id = $_GET['ground_id'];

// Include DB connection
include 'db_connection.php';

// Fetch ground details
$ground_result = $conn->query("SELECT * FROM grounds WHERE id = $ground_id");
$ground = $ground_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Page</title>
    <style>
        .confirmation-message {
            text-align: center;
            margin-top: 20px;
        }
        #qr-code {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <h1>Scan the QR Code , Pay & Wait</h1>
    <p>Ground: <?= htmlspecialchars($ground['name']) ?></p>
    <p>Price: ₹<?= htmlspecialchars($ground['price']) ?></p>

    <div class="confirmation-message">
        <p>Wait for approval</p>
    </div>

    <!-- Generate and Display a Simple QR Code (You can use libraries like php-qrcode for more complex QR code generation) -->
    <div id="qr-code">
        <img src="https://api.qrserver.com/v1/create-qr-code/?data=Booking%20ID:<?= urlencode($ground['id']) ?>&size=200x200" alt="QR Code" />
    </div>

    <div class="confirmation-message">
        <form action="confirmation_awaited.php" method="POST">
            <button type="submit">Next</button>
        </form>
    </div>
</body>
</html>
