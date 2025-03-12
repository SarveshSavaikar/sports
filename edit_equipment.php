<?php
session_start();

// Check if user is logged in and has the 'admin' role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

if (isset($_GET['id'])) {
    $equipment_id = $_GET['id'];

    // Fetch the current equipment details
    $query = "SELECT * FROM equipment WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $equipment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $equipment = $result->fetch_assoc();

    if (!$equipment) {
        echo "Equipment not found!";
        exit();
    }

    // Update equipment details if the form is submitted
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $location = $_POST['location'];
        $contact = $_POST['contact'];
        $price = $_POST['price'];
        $availability = $_POST['availability'];
        $sports_category = $_POST['sports_category'];
        $quantity = $_POST['quantity'];

        // Handle photo upload if new photo is provided
        $photo = $equipment['photo']; // Default to the existing photo
        if ($_FILES['photo']['name']) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $target_file; // New photo path
            }
        }

        // Update equipment details in the database
        $update_query = "UPDATE equipment SET name = ?, photo = ?, location = ?, contact = ?, price = ?, availability = ?, sports_category = ?, quantity = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssdsdii", $name, $photo, $location, $contact, $price, $availability, $sports_category, $quantity, $equipment_id);
        $stmt->execute();
        
        header("Location: manage_equipment.php");
    }
} else {
    echo "No equipment ID provided!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipment</title>
    <style>
        /* Add styles here */
    </style>
</head>
<body>

<h1>Edit Equipment</h1>

<form action="edit_equipment.php?id=<?= $equipment['id']; ?>" method="POST" enctype="multipart/form-data">
    <label for="name">Equipment Name</label>
    <input type="text" name="name" id="name" value="<?= htmlspecialchars($equipment['name']); ?>" required><br>

    <label for="location">Location</label>
    <input type="text" name="location" id="location" value="<?= htmlspecialchars($equipment['location']); ?>" required><br>

    <label for="contact">Contact</label>
    <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($equipment['contact']); ?>" required><br>

    <label for="price">Price</label>
    <input type="text" name="price" id="price" value="<?= htmlspecialchars($equipment['price']); ?>" required><br>

    <label for="availability">Availability</label>
    <input type="text" name="availability" id="availability" value="<?= htmlspecialchars($equipment['availability']); ?>" required><br>

    <label for="sports_category">Sports Category</label>
    <input type="text" name="sports_category" id="sports_category" value="<?= htmlspecialchars($equipment['sports_category']); ?>" required><br>

    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" id="quantity" value="<?= htmlspecialchars($equipment['quantity']); ?>" required><br>

    <label for="photo">Equipment Photo</label>
    <input type="file" name="photo" id="photo"><br>

    <button type="submit" name="submit">Update Equipment</button>
</form>

</body>
</html>
