<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php"); // Redirect if not logged in as customer
    exit();
}

$user = $_SESSION['user']; // Get customer data from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('uploads/photos/a.png') no-repeat center center fixed;
            background-size: cover;
            color: white; /* Set text color to white for visibility over the banner */
        }

        header {
            background-color: rgba(0, 0, 0, 0.5); /* Dark overlay for better contrast */
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
        }

        header p {
            font-size: 1.2em;
        }

        header a {
            color: #fff;
            text-decoration: none;
            background-color: #e74c3c;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
        }

        header a:hover {
            background-color: #c0392b;
        }

        nav {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(44, 62, 80, 0.8);
            padding: 10px 0;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        nav li {
            margin: 10px 20px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #34495e;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #1abc9c;
        }

        .container {
            padding: 20px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.7); /* Semi-transparent background */
            border-radius: 10px;
            margin-top: 20px;
        }

        .container h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #333;
        }

        .container p {
            font-size: 1.1em;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to the Customer Dashboard</h1>
        <p>Hello, <?php echo htmlspecialchars($user['name']); ?>!</p>
        <a href="logout.php">Logout</a>
    </header>

    <nav>
        <ul>
            <li><a href="book_appointment.php">Book Appointment</a></li>
            <li><a href="book_ground.php">Book Ground</a></li>
            <li><a href="buy_equipment.php">Buy Equipment</a></li>
            <li><a href="appointments.php">View Appointments</a></li>
            <li><a href="view_bookings.php">View Bookings</a></li>
            <li><a href="vieworder.php">View Equipment Order</a></li>
            <li><a href="view_cprofile.php">View Profile</a></li>
            <li><a href="manage_cprofile.php">Manage Profile</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Explore the Dashboard</h2>
        <p>Use the navigation above to manage your appointments, bookings, equipment orders, and profile.</p>
    </div>
</body>
</html>
