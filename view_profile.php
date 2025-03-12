<?php
session_start();

// Check if the user is logged in and has a valid role (Physiotherapist, Coach, or Nutritionist)
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['physiotherapist', 'coach', 'nutritionist'])) {
    header("Location: login.php"); // Redirect if not logged in or invalid role
    exit();
}

$user = $_SESSION['user']; // Get the user data from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
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
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            display: inline-block;
        }

        header a:hover {
            color: #c0392b;
        }

        .profile-container {
            background-color: #fff;
            border-radius: 10px;
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-container h2 {
            text-align: center;
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
        }

        .profile-container p {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 10px;
        }

        .profile-container strong {
            color: #555;
        }

        .profile-container img {
            display: block;
            margin: 20px auto;
            border-radius: 10px;
            max-width: 250px;
            max-height: 250px;
            object-fit: cover;
        }
        .profile-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.profile-row p {
    width: 48%; /* Two columns */
    font-size: 1.1em;
    color: #333;
}

        .profile-container a {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 1.1em;
            text-align: center;
        }

        .profile-container a:hover {
            background-color: #2980b9;
        }

        button {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 1em;
            display: inline-block;
        }

        button:hover {
            background-color: #c0392b;
        }

        .back-btn {
            margin: 20px;
        }
    </style>
</head>

<body>
    <header>
        <h1>View Profile</h1>
        <p>Hello, <?php echo $user['name']; ?>!</p>
        <a href="logout.php">Logout</a>
    </header>

    <div class="profile-container">
        <h2>Your Profile Information</h2>
        <p><strong>Profile Photo:</strong></p>
        <?php if (!empty($user['photo'])): ?>
            <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Photo">
        <?php else: ?>
            <p>No profile photo available.</p>
        <?php endif; ?>
        <div class="profile-row">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    </div>
    <div class="profile-row">
        <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($user['mobile_number']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
    </div>
    <div class="profile-row">
        <p><strong>Post:</strong> <?php echo htmlspecialchars($user['post']); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
    </div>
    <div class="profile-row">
        <p><strong>Prize Charged:</strong> <?php echo htmlspecialchars($user['prize_charged']); ?></p>
    </div>

        

        <!-- Display CV -->
        <p><strong></strong></p>
        <?php if (!empty($user['cv'])): ?>
            <a href="<?php echo htmlspecialchars($user['cv']); ?>" target="_blank">Download CV</a>
        <?php else: ?>
            <p>No CV uploaded.</p>
        <?php endif; ?>

        <br>
        <a href="manage_profile.php">Edit Profile</a>
    </div>

    <div class="back-btn">
        <button onclick="history.back()">Go Back</button>
    </div>
</body>
</html>
