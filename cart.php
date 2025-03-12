<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

// Get the user's cart items
$customer_id = $_SESSION['user']['id']; // Assuming the user's ID is stored in the session
$sql = "SELECT c.*, e.name AS equipment_name, e.price, e.photo
        FROM cart c
        JOIN equipment e ON c.equipment_id = e.id
        WHERE c.customer_id = $customer_id";
$result = $conn->query($sql);

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
        }

        .cart-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-item-info {
            flex: 1;
            margin-left: 20px;
        }

        .cart-item-info h4 {
            margin: 0;
            font-size: 1.2em;
            color: #555;
        }

        .cart-item-info p {
            margin: 5px 0;
            font-size: 1em;
        }

        .cart-item-info .total-price {
            font-weight: bold;
            color: #28a745;
            font-size: 1.1em;
        }

        .total {
            text-align: right;
            font-size: 1.3em;
            margin-top: 20px;
            font-weight: bold;
            color: #333;
        }

        .buttons {
            text-align: center;
            margin-top: 30px;
        }
        
        .button {
            text-align: center;
            margin-top: 30px;
        }

        .buttons a {
            display: inline-block;
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 15px;
            font-size: 1em;
            transition: background-color 0.3s;
        }
        .button a {
            display: inline-block;
            padding: 12px 25px;
            background-color: rgb(12, 143, 230);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 15px;
            font-size: 1em;
            transition: background-color 0.3s;
        }
        .buttons a:hover {
            background-color: #218838;
        }
        .button a:hover {
            background-color:rgb(12, 143, 230);
        }

        .cart-item .remove-btn {
            background-color: #dc3545;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cart-item .remove-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Your Cart</h1>
        <div class="button">
                <a href="buy_equipment.php">Back to Equipment</a>
    </div>
    </header>

    <div class="cart-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item_total = $row['price'] * $row['quantity'];
                $total += $item_total;
                ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="<?php echo htmlspecialchars($row['equipment_name']); ?>">
                    <div class="cart-item-info">
                        <h4><?php echo htmlspecialchars($row['equipment_name']); ?></h4>
                        <p>Price: Rs.<?php echo number_format($row['price'], 2); ?></p>
                        <!-- <p>Quantity: <?php echo $row['quantity']; ?></p> -->
                        <form action="update_quantity.php" method="POST" style="width : 50px;">
                            <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="action" value="decrease">-</button>
                            <span style="padding:20px;"><?php echo $row['quantity']; ?></span>
                            <button type="submit" name="action" value="increase">+</button>
                        </form>
                        <p class="total-price">Total: Rs.<?php echo number_format($item_total, 2); ?></p>
                    </div>
                    <form action="remove_from_cart.php" method="POST">
                        <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="remove-btn">Remove</button>
                    </form>
                </div>
                <?php
            }
            ?>
            <div class="total">
                Total: Rs.<?php echo number_format($total, 2); ?>
            </div>
            <div class="buttons">
                <a href="buy_equipment.php">Back to Equipment</a>
                <a href="payment.php">Proceed to Order</a>
            </div>
        <?php
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>
</body>
</html>
