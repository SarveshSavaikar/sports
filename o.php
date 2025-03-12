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
    <link rel="stylesheet" href="styles.css"> <!-- Link your CSS file here -->
    <style>
        /* Add custom styles for better appearance */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
        .table-container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .action-buttons a {
            color: #007bff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #e0e0e0;
        }
        .action-buttons a:hover {
            background-color: #ddd;
        }
        .action-not-available {
            color: #999;
        }
    </style>
</head>
<body>
    <h1>Manage Orders</h1>

    <div class="table-container">
        <table>
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
                        <td><?php echo '$' . number_format($row['total_amount'], 2); ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td class="action-buttons">
                            <?php if ($row['status'] == 'pending'): ?>
                                <a href="accept_reject_order.php?order_id=<?php echo $row['id']; ?>&action=accept">Accept</a> | 
                                <a href="accept_reject_order.php?order_id=<?php echo $row['id']; ?>&action=reject">Reject</a>
                            <?php else: ?>
                                <span class="action-not-available">Action Not Available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
