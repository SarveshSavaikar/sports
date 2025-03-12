<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Fetch all orders with customer names
$sql = "SELECT o.id, o.total_amount, o.status, c.name AS customer_name 
        FROM orders o
        JOIN users c ON o.customer_id = c.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
</head>
<body>
    <h1>Manage Orders</h1>

    <div class="table-container">
        <table border="1">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['total_amount']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <?php if ($row['status'] == 'pending'): ?>
                                <a href="accept_reject_order.php?order_id=<?php echo $row['id']; ?>&action=accept">Accept</a> | 
                                <a href="accept_reject_order.php?order_id=<?php echo $row['id']; ?>&action=reject">Reject</a>
                            <?php else: ?>
                                <span>Action Not Available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
