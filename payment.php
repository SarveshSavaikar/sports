<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user']['id'];

// Fetch the user's cart items
$sql = "SELECT c.*, e.name AS equipment_name, e.price, e.photo 
        FROM cart c
        JOIN equipment e ON c.equipment_id = e.id 
        WHERE c.customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0; // Initialize total amount

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Your cart is empty!";
    header("Location: view_cart.php");
    exit();
}

// Calculate total amount
while ($row = $result->fetch_assoc()) {
    $total += $row['price'] * $row['quantity'];
}

// Process payment status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['payment_done']) && $_POST['payment_done'] == 'yes') {
        // Insert order into `orders`
        $sql = "INSERT INTO orders (customer_id, total_amount, status, created_at, updated_at) 
                VALUES (?, ?, 'pending', NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $customer_id, $total);
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            // Insert items into `order_items`
            $result->data_seek(0); // Reset the pointer for reuse
            while ($row = $result->fetch_assoc()) {
                $equipment_id = $row['equipment_id'];
                $quantity = $row['quantity'];
                $sql = "INSERT INTO order_items (order_id, equipment_id, quantity_ordered) VALUES (?, ?, ?)";
                $item_stmt = $conn->prepare($sql);
                $item_stmt->bind_param("iii", $order_id, $equipment_id, $quantity);
                $item_stmt->execute();
            }

            // Clear the cart
            $sql = "DELETE FROM cart WHERE customer_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();

            $_SESSION['success'] = "Payment confirmed. Awaiting admin approval.";
            header("Location: googlepayscanner.php?order_id=$order_id");
            exit();
        } else {
        }
    } elseif (isset($_POST['payment_done']) && $_POST['payment_done'] == 'cash') {
        $sql = "INSERT INTO orders (customer_id, total_amount, status, created_at, updated_at) 
        VALUES (?, ?, 'pending', NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $customer_id, $total);
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            // Insert items into `order_items`
            $result->data_seek(0); // Reset the pointer for reuse
            while ($row = $result->fetch_assoc()) {
                $equipment_id = $row['equipment_id'];
                $quantity = $row['quantity'];
                $sql = "INSERT INTO order_items (order_id, equipment_id, quantity_ordered) VALUES (?, ?, ?)";
                $item_stmt = $conn->prepare($sql);
                $item_stmt->bind_param("iii", $order_id, $equipment_id, $quantity);
                $item_stmt->execute();
            }

            // Clear the cart
            $sql = "DELETE FROM cart WHERE customer_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();

            $_SESSION['success'] = "Payment confirmed. Awaiting admin approval.";
            header("Location: corder.php?order_id=$order_id");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid payment method!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Equipment Purchase</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .payment-container {
            width: 60%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: #f9f9f9;
        }

        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .payment-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .total {
            font-weight: bold;
            font-size: 1.2rem;
            text-align: right;
            margin-top: 20px;
        }

        .payment-methods {
            margin-top: 20px;
            text-align: center;
        }

        .buttons button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buttons button.yes {
            background: #28a745;
            color: #fff;
        }

        .buttons button.no {
            background: #dc3545;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="payment-container">
        <h1>Review Your Order</h1>
        <?php
        $result->data_seek(0); // Reset pointer
        while ($row = $result->fetch_assoc()) {
            $item_total = $row['price'] * $row['quantity'];
        ?>
            <div class="payment-item">
                <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Equipment Image">
                <div>
                    <strong><?php echo htmlspecialchars($row['equipment_name']); ?></strong>
                    <p>Price: Rs.<?php echo number_format($row['price'], 2); ?></p>
                    <p>Quantity: <?php echo $row['quantity']; ?></p>
                    <p>Item Total: Rs.<?php echo number_format($item_total, 2); ?></p>
                </div>
            </div>
        <?php } ?>
        <div class="total">Order Total: Rs.<?php echo number_format($total, 2); ?></div>



        <form method="post">
            <div class="buttons">
                <button type="submit" name="payment_done" value="yes" class="yes">Payment By GPAY</button>

                <button type="submit" name="payment_done" value="cash" class="yes">Cash On Delivery</button>
            </div>
        </form>

        <?php if (isset($_SESSION['success'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success'];
                                        unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error'];
                                    unset($_SESSION['error']); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>