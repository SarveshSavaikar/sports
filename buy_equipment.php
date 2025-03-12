<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

// Example: Fetch items from a database
include 'db_connection.php';

$sql = "SELECT * FROM equipment"; // Assuming you have an 'equipment' table
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Equipment</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .equipment-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px;
        }
        .equipment-box {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            width: 200px;
        }
        .equipment-box img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .add-to-cart {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-to-cart:disabled {
            background-color: #ccc;
        }
        .quantity-input {
            margin: 10px 0;
            width: 60px;
            text-align: center;
        }
    </style>
</head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>
    <header>
        <h1>Buy Equipment</h1>
        <a href="customer_dashboard.php">Back to Dashboard</a>
    </header>

    <div class="equipment-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="equipment-box">
                <img src="<?php echo $row['photo']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">

                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p>Price: Rs.<?php echo $row['price']; ?></p>
                <p>Available: <?php echo $row['quantity']; ?></p>
                <?php if ($row['quantity'] > 0) { ?>
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="equipment_id" value="<?php echo $row['id']; ?>">
                        <label for="quantity_<?php echo $row['id']; ?>">Quantity:</label>
                        <input 
                            type="number" 
                            id="quantity_<?php echo $row['id']; ?>" 
                            name="quantity" 
                            class="quantity-input" 
                            min="1" 
                            max="<?php echo $row['quantity']; ?>" 
                            value="1" 
                            required>
                        <button type="submit" class="add-to-cart">Add to Cart</button>
                    </form>
                <?php } else { ?>
                    <button class="add-to-cart" disabled>Out of Stock</button>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</body>
</html>
