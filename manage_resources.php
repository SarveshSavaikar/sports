<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

// Handle Delete Request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Get the photo path to delete the file
    $result = $conn->query("SELECT photo FROM grounds WHERE id = $id");
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row && file_exists($row['photo'])) {
            unlink($row['photo']);
        }

        // Delete the record
        $conn->query("DELETE FROM grounds WHERE id = $id");
        header("Location: manage_resources.php");
        exit();
    }
}

// Handle Add Resource
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update_id'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    $sports_category = $_POST['sports_category'];

    // File upload handling
    $photo = $_FILES['photo'];
    $photo_path = 'uploads/resources/' . basename($photo['name']);

    if (move_uploaded_file($photo['tmp_name'], $photo_path)) {
        $sql = "INSERT INTO grounds (name, photo, location, contact, price, availability, sports_category) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdis", $name, $photo_path, $location, $contact, $price, $availability, $sports_category);
        $stmt->execute();

        // Redirect to prevent form resubmission
        header("Location: manage_resources.php");
        exit();
    }
}

// Handle Update Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = $_POST['update_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    $sports_category = $_POST['sports_category'];

    $photo_path = $_POST['current_photo'];

    // If a new photo is uploaded
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo'];
        $photo_path = 'uploads/resources/' . basename($photo['name']);
        if (move_uploaded_file($photo['tmp_name'], $photo_path)) {
            // Delete old photo
            if (file_exists($_POST['current_photo'])) {
                unlink($_POST['current_photo']);
            }
        }
    }

    $sql = "UPDATE grounds SET name=?, photo=?, location=?, contact=?, price=?, availability=?, sports_category=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdssi", $name, $photo_path, $location, $contact, $price, $availability, $sports_category, $id);
    $stmt->execute();

    // Redirect to prevent form resubmission
    header("Location: manage_resources.php");
    exit();
}

// Fetch all resources
$result = $conn->query("SELECT * FROM grounds");

// Fetch a single resource for editing/viewing
$edit_resource = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM grounds WHERE id = $id");
    if ($edit_result) {
        $edit_resource = $edit_result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Grounds</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td img {
            max-width: 100px;
            height: auto;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
        }

        form label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        form input[type="text"], 
        form input[type="number"], 
        form input[type="file"], 
        form input[type="checkbox"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #45a049;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        .actions {
            display: flex;
            gap: 10px;
        }
    </style>

<!-- Back Button -->
<button onclick="history.back()">Go Back</button>
    <!-- Add Resource Form -->
    <?php if (!$edit_resource): ?>
        <h2>Add Ground</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact">

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="availability">Available:</label>
            <input type="checkbox" id="availability" name="availability" checked>

            <label for="sports_category">Sports Category:</label>
            <input type="text" id="sports_category" name="sports_category" required>

            <button type="submit">Add Ground</button>
        </form>
    <?php endif; ?>

    <!-- Edit Resource Form -->
    <?php if ($edit_resource): ?>
        <h2>Edit Ground</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="update_id" value="<?= $edit_resource['id'] ?>">
            <input type="hidden" name="current_photo" value="<?= $edit_resource['photo'] ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($edit_resource['name']) ?>" required>

            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            <p>Current Photo: <img src="<?= htmlspecialchars($edit_resource['photo']) ?>" alt="Photo" width="50"></p>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($edit_resource['location']) ?>" required>

            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($edit_resource['contact']) ?>">

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($edit_resource['price']) ?>" step="0.01" required>

            <label for="availability">Available:</label>
            <input type="checkbox" id="availability" name="availability" <?= $edit_resource['availability'] ? 'checked' : '' ?>>

            <label for="sports_category">Sports Category:</label>
            <input type="text" id="sports_category" name="sports_category" value="<?= htmlspecialchars($edit_resource['sports_category']) ?>" required>

            <button type="submit">Update Ground</button>
        </form>
        <a href="manage_resources.php">Cancel</a>
    <?php endif; ?>

    <!-- Resources Table -->
    <h2>Grounds</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Photo</th>
                <th>Location</th>
                <th>Contact</th>
                <th>Price</th>
                <th>Availability</th>
                <th>Sports Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><img src="<?= htmlspecialchars($row['photo']) ?>" alt="Photo" width="50"></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= htmlspecialchars($row['contact']) ?></td>
                        <td><?= htmlspecialchars($row['price']) ?></td>
                        <td><?= $row['availability'] ? 'Available' : 'Unavailable' ?></td>
                        <td><?= htmlspecialchars($row['sports_category']) ?></td>
                        <td>
                            <a href="manage_resources.php?edit=<?= $row['id'] ?>">Edit</a>
                            <a href="manage_resources.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this ground?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No grounds available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
