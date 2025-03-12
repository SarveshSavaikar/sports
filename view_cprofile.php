<?php
session_start();

// Check if the user is logged in and has a valid role (Customer)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
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
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Header Styling */
        header {
            background-color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 2em;
            color: #333;
        }

        header p {
            font-size: 1.2em;
            color: #555;
        }

        header a {
            font-size: 1em;
            color: #007bff;
            text-decoration: none;
        }

        header a:hover {
            text-decoration: underline;
        }

        /* Profile Container */
        .profile-container {
            background-color: white;
            border-radius: 10px;
            max-width: 700px;
            margin: 0 auto;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-container h2 {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
        }

        /* Profile Rows for Two Columns */
        .profile-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .profile-row p {
            width: 48%;
            font-size: 1.1em;
            color: #333;
        }

        /* Profile Photo */
        .profile-photo-section {
            margin-top: 20px;
        }

        .profile-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
            margin-top: 15px;
        }

        /* Edit Button */
        .edit-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1em;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Back Button -->
    <button onclick="history.back()">Go Back</button>

    <header>
        <h1>View Profile</h1>
        <p>Hello, <?php echo htmlspecialchars($user['name']); ?>!</p>
        <a href="logout.php">Logout</a>
    </header>

    <div class="profile-container">
        <h2>Your Profile Information</h2>
          <!-- Profile Photo Section -->
          <div class="profile-photo-section">
            <p><strong>Profile Photo:</strong></p>
            <?php if (!empty($user['photo'])): ?>
                <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Photo" class="profile-photo">
            <?php else: ?>
                <p>No profile photo available.</p>
            <?php endif; ?>
        </div>


        <!-- Profile Grid Layout -->
        <div class="profile-row">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        <div class="profile-row">
            <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($user['mobile_number']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
        </div>
        <div class="profile-row">
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
        </div>

      
        <br>
        <a href="manage_cprofile.php" class="edit-btn">Edit Profile</a>
    </div>

</body>
</html>
