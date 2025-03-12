<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Fetch all orders with customer names and items ordered
$sql = "SELECT o.id AS order_id, o.total_amount, o.status, c.name AS customer_name, 
        GROUP_CONCAT(CONCAT(e.name, ' (x', oi.quantity_ordered, ')') SEPARATOR ', ') AS items_ordered 
        FROM orders o
        JOIN users c ON o.customer_id = c.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN equipment e ON oi.equipment_id = e.id
        GROUP BY o.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .accept-btn {
            background-color: #4CAF50;
            color: white;
        }
        .reject-btn {
            background-color: #f44336;
            color: white;
        }
        .reverse-btn {
            background-color: #ffa500;
            color: white;
        }
    </style>
</head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>
    <h1>Manage Orders</h1>

    <?php
    if (isset($_SESSION['success'])) {
        echo "<p style='color: green;'>{$_SESSION['success']}</p>";
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']);
    }
    ?>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Items Ordered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo 'Rs.' . number_format($row['total_amount'], 2); ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td><?php echo $row['items_ordered'] ? $row['items_ordered'] : 'No items'; ?></td>
                        <td>
                            <?php if ($row['status'] == 'pending'): ?>
                                <a href="accept_reject_order.php?order_id=<?php echo $row['order_id']; ?>&action=accept">
                                    <button class="accept-btn">Accept</button>
                                </a>
                                <a href="accept_reject_order.php?order_id=<?php echo $row['order_id']; ?>&action=reject">
                                    <button class="reject-btn">Reject</button>
                                </a>
                            <?php else: ?>
                                <a href="accept_reject_order.php?order_id=<?php echo $row['order_id']; ?>&action=pending">
                                    <button class="reverse-btn">Reverse</button>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
