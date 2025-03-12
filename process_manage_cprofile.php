<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include 'db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $mobile_number = htmlspecialchars($_POST['mobile_number']);
    $address = htmlspecialchars($_POST['address']);
    $dob = htmlspecialchars($_POST['dob']) ?: null;

    // Handle profile photo upload
    $photo_path = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo_dir = 'uploads/photos/';
        $photo_name = uniqid() . '_' . basename($_FILES['photo']['name']);
        $photo_path = $photo_dir . $photo_name;

        // Move the uploaded file to the directory
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
            echo "Error uploading the file.";
            exit();
        }
    }

    // Get the user ID from session
    $user_id = $_SESSION['user']['id'];

    // Update the user's information in the database
    $sql = "UPDATE users SET 
                name = ?, 
                email = ?, 
                mobile_number = ?, 
                address = ?, 
                dob = ?, 
                photo = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ssssssi", $name, $email, $mobile_number, $address, $dob, $photo_path, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Update session data
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['mobile_number'] = $mobile_number;
        $_SESSION['user']['address'] = $address;
        $_SESSION['user']['dob'] = $dob;
        $_SESSION['user']['photo'] = $photo_path;

        // Redirect to profile page or display success message
        header("Location: customer_dashboard.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
