<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'goa_scores';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

// Check if the user exists and credentials are correct
$query = "SELECT * FROM users WHERE email = ? AND password = ? AND role = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $email, $password, $role);
$stmt->execute();
$result = $stmt->get_result();
echo $role;

if ($result->num_rows > 0) {
    // User found, create session and redirect to corresponding dashboard
    $_SESSION['user'] = $result->fetch_assoc(); // Store user data in session

    // Redirect based on role
    if ($role == 'admin') {
        header("Location: admin_dashboard.php");
    } elseif ($role == 'coach') {
        header("Location: coach_dashboard.php");
    } elseif ($role == 'physiotherapist') {
        header("Location: physiotherapist_dashboard.php");
    } elseif ($role == 'nutritionist') {
        header("Location: nutritionist_dashboard.php");
    } elseif($role == 'groundmanager'){
        header("Location: groundmanager_dashboard.php");
    } else{
        header("Location: customer_dashboard.php");
    }
} else {
    // Invalid credentials
    echo "Invalid login credentials!";
}

$stmt->close();
$conn->close();
?>
