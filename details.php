<?php
include 'db_connection.php';

// Handle Add, Update, and Delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        // Add new club details
        $name = $_POST['name'];
        $description = $_POST['description'];
        $image = $_FILES['image'];

        $target_dir = "uploads/clubs/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($image['name']);
        $upload_ok = true;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

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
            $insert_query = "INSERT INTO club_details (name, description, image_path) VALUES ('$name', '$description', '$target_file')";
            if ($conn->query($insert_query) === TRUE) {
                $message = "Club details added successfully!";
            } else {
                $message = "Error adding club details: " . $conn->error;
            }
        } else {
            $message = "Error uploading image.";
        }
    } elseif (isset($_POST['update'])) {
        // Update club details
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $update_query = "UPDATE club_details SET name = '$name', description = '$description' WHERE id = $id";

        if ($conn->query($update_query) === TRUE) {
            $message = "Club details updated successfully!";
        } else {
            $message = "Error updating club details: " . $conn->error;
        }
    } elseif (isset($_POST['delete'])) {
        // Delete club details
        $id = $_POST['id'];
        $delete_query = "DELETE FROM club_details WHERE id = $id";

        if ($conn->query($delete_query) === TRUE) {
            $message = "Club details deleted successfully!";
        } else {
            $message = "Error deleting club details: " . $conn->error;
        }
    }
}

// Fetch existing club details
$fetch_query = "SELECT * FROM club_details";
$club_details = $conn->query($fetch_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Details</title>
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

        .form-container, .table-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container form label {
            display: block;
            margin: 10px 0 5px;
        }

        .form-container form input,
        .form-container form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container form button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .action-buttons button {
            margin-right: 5px;
            padding: 5px 10px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-buttons button.delete {
            background-color: #f44336;
        }
    </style>
</head></head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>

<h1>Club Details Management</h1>

<div class="form-container">
    <h2>Add Club Details</h2>
    <?php if (isset($message)) { echo "<p style='color: red;'>$message</p>"; } ?>
    <form action="details.php" method="post" enctype="multipart/form-data">
        <label for="name">Club Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Club Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <button type="submit" name="add">Add Club</button>
    </form>
</div>

<div class="table-container">
    <h2>Existing Club Details</h2>
    <table>
        <thead>
            <tr>
                <th>Club Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($club_details->num_rows > 0): ?>
                <?php while ($row = $club_details->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['description']); ?></td>
                        <td><img src="<?= htmlspecialchars($row['image_path']); ?>" alt="Club Image" class="image-preview"></td>
                        <td class="action-buttons">
                            <form action="details.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <button type="submit" name="delete" class="delete">Delete</button>
                            </form>
                            <form action="update_club.php" method="get" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <button type="submit" name="edit">Edit</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No club details available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
