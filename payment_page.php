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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate payment success
    $payment_status = 'successful'; // Integrate payment gateway here
    $user = $_SESSION['user'];

    // Add booking details to the database
    $conn->query("INSERT INTO bookings (user_id, ground_id, status) VALUES ({$user['id']}, $ground_id, '$payment_status')");

    // Update the ground availability
    $conn->query("UPDATE grounds SET availability = 0 WHERE id = $ground_id");

    echo "Payment successful! Your booking is confirmed.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
</head>
<body>
    <h1>Complete Your Payment</h1>
    <p>Ground: <?= htmlspecialchars($ground['name']) ?></p>
    <p>Price: <?= htmlspecialchars($ground['price']) ?></p>

    <!-- Simulate Payment -->
    <form method="POST">
        <button type="submit">Pay Now</button>
    </form>
</body>
</html>
