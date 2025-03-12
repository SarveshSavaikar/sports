<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation Awaited</title>
</head>
<body>
    <h1>Booking Confirmed</h1>
    <p>Your booking is now under review. Please wait for admin approval.</p>
</body>
</html>
