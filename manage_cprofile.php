<?php
session_start();

// Check if the user is logged in and has a valid role (Physiotherapist, Coach, or Nutritionist)
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['customer'])) {
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
    <title>Manage Profile</title>
    <link rel="stylesheet" href="styles.css">
</head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>
    <header>
        <h1>Manage Profile</h1>
        <p>Hello, <?php echo $user['name']; ?>!</p>
    </header>

    <form action="process_manage_cprofile.php" method="post" enctype="multipart/form-data">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="mobile_number">Mobile Number:</label>
        <input type="text" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($user['mobile_number']); ?>" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>


        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>">

        <!-- Profile Photo Upload -->
        <label for="photo">Profile Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*">

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
