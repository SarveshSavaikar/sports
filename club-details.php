<?php
include 'db_connection.php';

// Fetch all club details
$fetch_query = "SELECT * FROM club_details";
$club_details = $conn->query($fetch_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Details</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #4CAF50, #2E8B57);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }

        .club-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .club {
            display: flex;
            flex-wrap: wrap;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .club:hover {
            transform: scale(1.02);
        }

        .club img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-right: 2px solid #ddd;
        }

        .club-info {
            padding: 20px;
            flex: 1;
        }

        .club-info h2 {
            margin: 0 0 10px;
            color: #2E8B57;
            font-size: 22px;
        }

        .club-info p {
            margin: 0;
            color: #444;
            line-height: 1.5;
        }

        .no-club-message {
            text-align: center;
            color: white;
            font-size: 18px;
        }
    </style>
</head>
<body>

<h1>Club Details</h1>

<div class="club-container">
    <?php if ($club_details->num_rows > 0): ?>
        <?php while ($row = $club_details->fetch_assoc()): ?>
            <div class="club">
                <img src="<?= htmlspecialchars($row['image_path']); ?>" alt="Club Image">
                <div class="club-info">
                    <h2><?= htmlspecialchars($row['name']); ?></h2>
                    <p><?= htmlspecialchars($row['description']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-club-message">No clubs available at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>
