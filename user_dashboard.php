<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <style>
    .container {
      padding: 50px;
      max-width: 600px;
      margin: 0 auto;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      font-size: 2rem;
      color: #333;
      margin-bottom: 20px;
    }

    .role {
      font-size: 1.2rem;
      color: #555;
    }

    .dashboard-links {
      margin-top: 30px;
    }

    .dashboard-links a {
      display: block;
      margin: 10px 0;
      color: #007bff;
      text-decoration: none;
    }

    .dashboard-links a:hover {
      text-decoration: underline;
    }

    .logout {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #f44336;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .logout:hover {
      background-color: #d32f2f;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Welcome, <?php echo $name; ?>!</h1>
    <p class="role">You are logged in as: <?php echo $role; ?></p>

    <div class="dashboard-links">
        <!-- Links for Coach and Player -->
        <?php if ($role == 'Coach' || $role == 'Player'): ?>
            <a href="edit_profile.php">Edit Your Profile</a>
            <a href="appointment_booking.php">Book Appointment with Nutritionist/Physiotherapist</a>
            <a href="rent_ground_equipment.php">Rent Grounds/Equipment</a>
        <?php endif; ?>

        <!-- Links for Physiotherapist and Nutritionist -->
        <?php if ($role == 'Physiotherapist' || $role == 'Nutritionist'): ?>
            <a href="view_appointments.php">View Appointments</a>
        <?php endif; ?>

        <!-- Admin users have different options (not shown here) -->
        <?php if ($role == 'Admin'): ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="add_ground_equipment.php">Add Ground/Equipment</a>
        <?php endif; ?>
    </div>

    <form action="logout.php" method="POST">
      <button class="logout" type="submit">Logout</button>
    </form>
  </div>
</body>
</html>
