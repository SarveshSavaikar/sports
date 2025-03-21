<?php
session_start();

// Ensure the user is logged in and is a customer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user']; // Get customer data from session
$customer_id = $user['id']; // Assuming customer ID is stored in session

// Include DB connection
include 'db_connection.php';

// Retrieve booking details from the GET request
$ground_id = $_GET['ground_id'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
$start_time = $_GET['start_time'];
$end_time = $_GET['end_time'];
$total_time = $_GET['total_time'];
$total_price = $_GET['total_price'];
$JSON = $_GET['JSON'];
$number_of_dates = $_GET['number_of_dates'];
// Fetch ground details
$stmt = $conn->prepare("SELECT name, price FROM grounds WHERE id = ?");
$stmt->bind_param("i", $ground_id);
$stmt->execute();
$ground_result = $stmt->get_result();
$ground = $ground_result->fetch_assoc();
$stmt->close();

// Confirm booking process
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_status = $_POST['payment_status']; // 'yes' or 'no'
    $number_of_dates = $_POST['number_of_dates'];

    if ($payment_status == 'yes') {
        $bookings_id = strtotime("now") . $customer_id; // Generate a unique booking ID
        // Insert booking details into the database
        $stmt = $conn->prepare("INSERT INTO bookings (id, ground_id, start_date, end_date, start_time, end_time, total_time, total_price, payment_status, status , bookings_id , booking_slots) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,? , ?)");
        $status = 'pending'; // Initial status
        $stmt->bind_param("iissssddssss", $customer_id, $ground_id, $start_date, $end_date, $start_time, $end_time, $total_time, $total_price, $payment_status, $status, $bookings_id, $JSON);

        if ($stmt->execute()) {
            // Redirect to confirmation page
            header("Location: confirmation_awaited.php");
            exit();
        } else {
            echo "Booking failed. Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        // Redirect back to booking page if payment is not completed
        header("Location: book_ground.php?ground_id=$ground_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        h1,
        h3 {
            text-align: left;
            color: #333;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .booking-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .booking-details p {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
            flex: 1 1 45%;
        }

        .confirmation-form {
            text-align: center;
            margin-top: 20px;
        }

        .confirmation-form button {
            padding: 12px 25px;
            margin: 10px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .confirmation-form button:hover {
            background-color: #45a049;
        }

        .confirmation-form button:focus {
            outline: none;
        }

        .confirmation-form button[value="yes"] {
            background-color: #4CAF50;
            color: white;
        }

        .confirmation-form button[value="no"] {
            background-color: #f44336;
            color: white;
        }

        .confirmation-form button[value="yes"]:hover {
            background-color: #45a049;
        }

        .confirmation-form button[value="no"]:hover {
            background-color: #e53935;
        }

        img {
            max-width: 50%;
            height: auto;
            margin-top: 20px;
            border: 5px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 20px auto;
        }
    </style>
</head>

<body>

    <header>
        <h1>Booking Confirmation</h1>
    </header>

    <div class="container">
        <h1>Customer Details</h1>
        <h4><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></h4>
        <div class="booking-details">

            <p><strong>Ground Name:</strong> <?= htmlspecialchars($ground['name']) ?></p>
            <p><strong>Price per Hour:</strong> ₹<?= htmlspecialchars($ground['price']) ?></p>
        </div>

        <h1>Booking Details</h1>
        <div class="booking-details">
            <?php
            // Ensure JSON is decoded properly
            $data = json_decode($JSON, true);
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            // Check if JSON decoding was successful
            if ($data === null) {
                echo "<p>Error: Invalid booking data.</p>";
            } else {
                foreach ($data as $key => $value) {
                    // Ensure $value is an array before accessing indexes
                    if (is_array($value) && count($value) >= 2) {
            ?>
                        <p><strong>Date:</strong> <?= htmlspecialchars($key) ?></p>
                        <p><strong>Starting Time:</strong> <?= htmlspecialchars($value[0]) ?></p>
                        <p><strong>Ending Time:</strong> <?= htmlspecialchars($value[1]) ?></p>
            <?php
                    } else {
                        echo "<p>Error: Invalid time format for date $key.</p>";
                    }
                }
            }
            ?>

            <p><strong>Total Time:</strong> <?= htmlspecialchars($total_time) ?> hours</p>
            <p><strong>Total Price:</strong> ₹<?= htmlspecialchars($total_price) ?></p>
        </div>


        <h2>Confirm Booking</h2>
        <div class="confirmation-form">
            <form method="POST" action="">
                <button type="submit" name="payment_status" value="yes">Yes</button>
                <button type="submit" name="payment_status" value="no">No</button>
            </form>
        </div>
    </div>

</body>

</html>