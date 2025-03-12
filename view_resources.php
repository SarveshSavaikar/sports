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
    <title>View Resources</title>
</head>
<body>
    <h1>Resources</h1>
    <div class="resource-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="resource-box">
                <img src="<?php echo $row['photo']; ?>" alt="<?php echo $row['name']; ?>" width="150" height="150">
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
