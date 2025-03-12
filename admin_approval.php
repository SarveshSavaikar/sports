<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Include DB connection
include 'db_connection.php';

// Fetch all pending booking requests
$result = $conn->query("SELECT * FROM booking_requests WHERE status = 'pending'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    // Update booking status based on approval or rejection
    $conn->query("UPDATE booking_requests SET status = '$status' WHERE id = $booking_id");

    // Optionally, update the ground availability based on approval
    if ($status == 'approved') {
        $ground_id = $_POST['ground_id'];
        $conn->query("UPDATE grounds SET availability = 0 WHERE id = $ground_id"); // Mark ground as booked
    }

    header("Location: admin_approval.php"); // Refresh the page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Approve or Reject Booking</title>
</head>
<body>
    <h1>Pending Booking Requests</h1>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div>
            <p><strong>User ID:</strong> <?= htmlspecialchars($row['user_id']) ?></p>
            <p><strong>Ground ID:</strong> <?= htmlspecialchars($row['ground_id']) ?></p>

            <form method="POST">
                <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                <input type="hidden" name="ground_id" value="<?= $row['ground_id'] ?>">
                <button type="submit" name="status" value="approved">Approve</button>
                <button type="submit" name="status" value="rejected">Reject</button>
            </form>
        </div>
    <?php endwhile; ?>

</body>
</html>
