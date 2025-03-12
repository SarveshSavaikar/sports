<?php
include 'db_connection.php';

// Check if ID is passed in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request.");
}

$id = $_GET['id'];

// Fetch the club details for the given ID
$fetch_query = "SELECT * FROM club_details WHERE id = $id";
$result = $conn->query($fetch_query);

if ($result->num_rows === 0) {
    die("Club not found.");
}

$club = $result->fetch_assoc();

// Handle form submission to update details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    $target_dir = "uploads/clubs/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Check if a new image is uploaded
    if (!empty($image['name'])) {
        $target_file = $target_dir . basename($image['name']);
        $upload_ok = true;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate the uploaded image
        $check = getimagesize($image['tmp_name']);
        if ($check === false) {
            $upload_ok = false;
            $message = "File is not an image.";
        }

        if (!in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            $upload_ok = false;
            $message = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }

        if ($upload_ok && move_uploaded_file($image['tmp_name'], $target_file)) {
            $image_path = $target_file;
        } else {
            $message = "Error uploading image.";
        }
    } else {
        $image_path = $club['image_path']; // Retain the existing image path if no new image is uploaded
    }

    // Update the club details in the database
    $update_query = "UPDATE club_details SET name = '$name', description = '$description', image_path = '$image_path' WHERE id = $id";
    if ($conn->query($update_query) === TRUE) {
        $message = "Club details updated successfully!";
        // Refresh the details from the database
        $result = $conn->query($fetch_query);
        $club = $result->fetch_assoc();
    } else {
        $message = "Error updating club details: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Club Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin: 10px 0 5px;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head></head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>

<h1>Update Club Details</h1>

<div class="form-container">
    <h2>Edit Details for "<?= htmlspecialchars($club['name']); ?>"</h2>
    <?php if (isset($message)) { echo "<p style='color: red;'>$message</p>"; } ?>
    <form action="update_club.php?id=<?= $id; ?>" method="post" enctype="multipart/form-data">
        <label for="name">Club Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($club['name']); ?>" required>

        <label for="description">Club Description:</label>
        <textarea id="description" name="description" rows="4" required><?= htmlspecialchars($club['description']); ?></textarea>

        <label for="image">Current Image:</label><br>
        <img src="<?= htmlspecialchars($club['image_path']); ?>" alt="Current Club Image" class="image-preview"><br>

        <label for="image">Upload New Image (Optional):</label>
        <input type="file" id="image" name="image" accept="image/*">

        <button type="submit">Update Club</button>
    </form>
</div>

</body>
</html>
