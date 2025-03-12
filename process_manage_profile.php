<?php
session_start();
include 'db_connection.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Retrieve updated data from the form
$name = $_POST['name'];
$email = $_POST['email'];
$mobile_number = $_POST['mobile_number'];
$address = $_POST['address'];
$post = $_POST['post'];
$dob = $_POST['dob'];
$prize_charged = $_POST['prize_charged'];

// Handle file uploads
$photo_path = $_SESSION['user']['photo'];
if (!empty($_FILES['photo']['name'])) {
    $photo_path = "uploads/photos/" . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
}

$cv_path = $_SESSION['user']['cv'];
if (!empty($_FILES['cv']['name'])) {
    $cv_path = "uploads/cv/" . basename($_FILES['cv']['name']);
    move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path);
}

// Update user details in the database
$query = "UPDATE users SET name = ?, email = ?, mobile_number = ?, address = ?, post = ?, dob = ?, prize_charged = ?, photo = ?, cv = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssssssi", $name, $email, $mobile_number, $address, $post, $dob, $prize_charged, $photo_path, $cv_path, $user_id);

if ($stmt->execute()) {
    // Update session with the new values
    $_SESSION['user'] = [
        'id' => $user_id,
        'name' => $name,
        'email' => $email,
        'mobile_number' => $mobile_number,
        'address' => $address,
        'post' => $post,
        'dob' => $dob,
        'prize_charged' => $prize_charged,
        'photo' => $photo_path,
        'cv' => $cv_path,
        'role' => $_SESSION['user']['role'], // Retain the role
    ];

    header("Location: manage_profile.php?status=success");
} else {
    echo "Error updating profile: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
