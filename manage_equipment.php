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
    $result = $conn->query("SELECT photo FROM equipment WHERE id = $id");
    $row = $result->fetch_assoc();
    if ($row && file_exists($row['photo'])) {
        unlink($row['photo']);
    }

    // Delete the record
    $conn->query("DELETE FROM equipment WHERE id = $id");
    header("Location: manage_equipment.php"); // Redirect to avoid resubmission
    exit();
}

// Fetch all equipment
$result = $conn->query("SELECT * FROM equipment");

// Handle Add Equipment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update_id'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];
    $price = $_POST['price'];
    $availability = isset($_POST['availability']) ? 1 : 0;
    $sports_category = $_POST['sports_category'];
    $quantity = $_POST['quantity'];

    // File upload handling
    $photo = $_FILES['photo'];
    $photo_path = 'uploads/equipment/' . basename($photo['name']);

    if (move_uploaded_file($photo['tmp_name'], $photo_path)) {
        $sql = "INSERT INTO equipment (name, photo, location, contact, price, availability, sports_category, quantity) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdisi", $name, $photo_path, $location, $contact, $price, $availability, $sports_category, $quantity);
        $stmt->execute();
    }

    // Redirect after insert
    header("Location: manage_equipment.php"); // Redirect to avoid resubmission
    exit();
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
    $quantity = $_POST['quantity'];

    $photo_path = $_POST['current_photo'];

    // If a new photo is uploaded
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo'];
        $photo_path = 'uploads/equipment/' . basename($photo['name']);
        if (move_uploaded_file($photo['tmp_name'], $photo_path)) {
            // Delete old photo
            if (file_exists($_POST['current_photo'])) {
                unlink($_POST['current_photo']);
            }
        }
    }

    $sql = "UPDATE equipment SET name=?, photo=?, location=?, contact=?, price=?, availability=?, sports_category=?, quantity=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdissi", $name, $photo_path, $location, $contact, $price, $availability, $sports_category, $quantity, $id);
    $stmt->execute();

    // Redirect after update
    header("Location: manage_equipment.php"); // Redirect to avoid resubmission
    exit();
}

// Fetch a single equipment for editing/viewing
$edit_equipment = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM equipment WHERE id = $id");
    $edit_equipment = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Equipment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #004d99;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 30px auto;
        }
        h1, h2 {
            color: #333;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        label {
            display: block;
            font-weight: bold;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        input[type="checkbox"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="checkbox"] {
            width: auto;
            display: inline-block;
        }
        button {
            background-color: #004d99;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #003366;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
        }
        table img {
            max-width: 50px;
        }
        .action-links a {
            color: #004d99;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #004d99;
            border-radius: 4px;
            margin-right: 10px;
        }
        .action-links a:hover {
            background-color: #004d99;
            color: white;
        }
    </style>
</head><!-- Back Button -->
<button onclick="history.back()">Go Back</button>
<body>
    <header>
        <h1>Equipment Management</h1>
    </header>

    <div class="container">
        <!-- Add Equipment Form -->
        <?php if (!$edit_equipment): ?>
            <h2>Add Equipment</h2>
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

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" required>

                <button type="submit">Add Equipment</button>
            </form>
        <?php endif; ?>

        <!-- Edit Equipment Form -->
        <?php if ($edit_equipment): ?>
            <h2>Edit Equipment</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_id" value="<?= $edit_equipment['id'] ?>">
                <input type="hidden" name="current_photo" value="<?= $edit_equipment['photo'] ?>">

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($edit_equipment['name']) ?>" required>

                <label for="photo">Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <p>Current Photo: <img src="<?= htmlspecialchars($edit_equipment['photo']) ?>" alt="Photo"></p>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?= htmlspecialchars($edit_equipment['location']) ?>" required>

                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($edit_equipment['contact']) ?>">

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="<?= htmlspecialchars($edit_equipment['price']) ?>" step="0.01" required>

                <label for="availability">Available:</label>
                <input type="checkbox" id="availability" name="availability" <?= $edit_equipment['availability'] ? 'checked' : '' ?>>

                <label for="sports_category">Sports Category:</label>
                <input type="text" id="sports_category" name="sports_category" value="<?= htmlspecialchars($edit_equipment['sports_category']) ?>" required>

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="<?= $edit_equipment['quantity'] ?>" required>

                <button type="submit">Update Equipment</button>
            </form>
            <a href="manage_equipment.php">Cancel</a>
        <?php endif; ?>

        <!-- Equipment Table -->
        <h2>Equipment List</h2>
        <table>
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
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
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
                        <td><?= $row['quantity'] ?></td>
                        <td class="action-links">
                            <a href="manage_equipment.php?edit=<?= $row['id'] ?>">Edit</a>
                            <a href="manage_equipment.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this equipment?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
