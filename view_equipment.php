<?php
include 'db_connection.php';

$sql = "SELECT * FROM equipment";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Equipment</title>
    <style>
        .equipment-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .equipment-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }
        .equipment-box img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Available Equipment</h1>
    <div class="equipment-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="equipment-box">
                <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p>Location: <?php echo htmlspecialchars($row['location']); ?></p>
                <p>Contact: <?php echo htmlspecialchars($row['contact']); ?></p>
                <p>Price: $<?php echo htmlspecialchars($row['price']); ?></p>
                <p>Available: <?php echo $row['availability'] ? 'Yes' : 'No'; ?></p>
                <p>Category: <?php echo htmlspecialchars($row['sports_category']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
