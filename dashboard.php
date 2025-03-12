<?php
session_start();

// Check if user is logged in and get the user role from session
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role']; // Role: admin, coach, physiotherapist, nutritionist, customer
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GOA SCORES</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to GOA SCORES Dashboard</h1>
        <p>User: <?php echo ucfirst($username); ?> | Role: <?php echo ucfirst($role); ?></p>
    </header>

    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <?php if ($role == 'admin') : ?>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="manage_bookings.php">Manage Bookings</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
            <?php elseif (in_array($role, ['coach', 'physiotherapist', 'nutritionist'])) : ?>
                <li><a href="view_appointments.php">View Appointments</a></li>
                <li><a href="edit_profile.php">Manage Profile</a></li>
            <?php elseif ($role == 'customer') : ?>
                <li><a href="book_appointment.php">Book Appointments</a></li>
                <li><a href="view_bookings.php">View Bookings</a></li>
                <li><a href="browse_profiles.php">Browse Profiles</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h2>Dashboard Overview</h2>

        <?php if ($role == 'admin') : ?>
            <section>
                <h3>Admin Controls</h3>
                <p>Manage users, appointments, and bookings from the admin panel.</p>
                <ul>
                    <li><a href="manage_appointments.php">View and Manage Appointments</a></li>
                    <li><a href="manage_bookings.php">View and Manage Bookings</a></li>
                    <li><a href="manage_users.php">View and Manage User Profiles</a></li>
                </ul>
            </section>
        <?php elseif ($role == 'coach' || $role == 'physiotherapist' || $role == 'nutritionist') : ?>
            <section>
                <h3>Professional Dashboard</h3>
                <p>View your appointments and manage your profile.</p>
                <ul>
                    <li><a href="view_appointments.php">View Appointments</a></li>
                    <li><a href="edit_profile.php">Edit Your Profile</a></li>
                </ul>
            </section>
        <?php elseif ($role == 'customer') : ?>
            <section>
                <h3>Customer Dashboard</h3>
                <p>Book appointments, view your bookings, and browse professional profiles.</p>
                <ul>
                    <li><a href="book_appointment.php">Book an Appointment</a></li>
                    <li><a href="view_bookings.php">View Your Bookings</a></li>
                    <li><a href="browse_profiles.php">Browse Profiles</a></li>
                </ul>
            </section>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 GOA SCORES. All rights reserved.</p>
    </footer>
</body>
</html>
