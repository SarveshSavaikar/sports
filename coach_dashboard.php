<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and is a coach
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'coach') {
    header("Location: login.php");
    exit();
}

// Get user data from the session
$user = $_SESSION['user'];

echo "Welcome, " . $user['name'] . "! You can manage your coaching tasks here.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Dashboard</title>
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

        /* Go Back Button Styling */
        button {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px;
        }

        button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, Coach</h1>
        <p>Hello, <?php echo $user['name']; ?>!</p>
        <a href="logout.php">Logout</a>
    </header>

    <nav>
        <ul>
            <li><a href="manage_profile.php">Manage Profile</a></li>
            <li><a href="view_profile.php">View Profile</a></li>
            <li><a href="professional_dashboard.php">View Appointments</a></li>
        </ul>
    </nav>

    <!-- Go Back Button -->
    <div class="container">
        <button onclick="history.back()">Go Back</button>
    </div>
</body>
</html>
