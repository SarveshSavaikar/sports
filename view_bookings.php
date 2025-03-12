<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Fetch bookings for the logged-in user
$user_id = $_SESSION['user']['id'];
$query = "SELECT bookings.id AS booking_id, bookings.ground_id, 
                 bookings.start_date, bookings.end_date, bookings.start_time, bookings.end_time, 
                 bookings.total_time, bookings.total_price, bookings.status , bookings.bookings_id , bookings.adminstatus
          FROM bookings
          WHERE bookings.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }

        .table-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            font-size: 16px;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table td {
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .pending {
            background-color: #ffeb3b;
            color: #333;
        }

        .accepted {
            background-color: #4CAF50;
            color: white;
        }

        .rejected {
            background-color: #f44336;
            color: white;
        }

    </style>
</head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>
    <h1>Your Bookings</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Ground ID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Total Time</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($booking = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $booking['booking_id']; ?></td>
                        <td><?= $booking['ground_id']; ?></td>
                        <td><?= htmlspecialchars($booking['start_date']); ?></td>
                        <td><?= htmlspecialchars($booking['end_date']); ?></td>
                        <td><?= htmlspecialchars($booking['start_time']); ?></td>
                        <td><?= htmlspecialchars($booking['end_time']); ?></td>
                        <td><?= htmlspecialchars($booking['total_time']); ?> hours</td>
                        <td>₹<?= htmlspecialchars($booking['total_price']); ?></td>
                        <td>
                            <span class="status 
                            <?php 
                            if( $booking['adminstatus'] == 'pending'){
                                echo 'pending';
                            }
                            elseif($booking['adminstatus'] == 'accepted'){
                               echo 'accepted';
                            }
                            else{
                                echo 'rejected';
                            }
                            // if ($booking['status'] == 'pending') {
                            //     echo 'pending';
                            // } elseif ($booking['status'] == 'accepted') {
                            //     echo 'accepted';
                            // } else {
                            //     echo 'rejected';
                            // }
                            ?>">
                            <?= ucfirst($booking['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
