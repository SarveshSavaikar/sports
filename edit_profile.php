<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "goa_scores";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current user details
// Assuming user_id is actually 'id' in the database
$user_id = $_SESSION['user_id']; // or $user_id = $_SESSION['id'] if you store it like that

$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Fetch user data
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mobile_number = $_POST['mobile_number'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $price_charged = $_POST['price_charged'];

    // Handle file upload for CV and Photo
    $cv = "";
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $cv = "uploads/" . basename($_FILES['cv']['name']);
        move_uploaded_file($_FILES['cv']['tmp_name'], $cv);
    }

    $photo = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = "uploads/" . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }

    // Update the database with the new details
    $update_sql = "UPDATE users SET 
                    mobile_number = '$mobile_number', 
                    address = '$address', 
                    cv = '$cv', 
                    photo = '$photo', 
                    date_of_birth = '$date_of_birth', 
                    price_charged = '$price_charged' 
                    WHERE user_id = '$user_id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "Profile updated successfully!";
        header("Location: user_dashboard.php"); // Redirect back to dashboard after successful update
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
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
      margin-bottom: 30px;
    }

    label {
      display: block;
      margin: 10px 0 5px;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    button {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error {
      color: red;
      font-size: 0.9rem;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Edit Profile</h1>
    <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
      <label for="mobile_number">Mobile Number</label>
      <input type="text" name="mobile_number" value="<?php echo $user['mobile_number']; ?>" required>

      <label for="address">Address</label>
      <textarea name="address" rows="4" required><?php echo $user['address']; ?></textarea>

      <label for="date_of_birth">Date of Birth</label>
      <input type="date" name="date_of_birth" value="<?php echo $user['date_of_birth']; ?>" required>

      <label for="price_charged">Price Charged (per session)</label>
      <input type="number" step="0.01" name="price_charged" value="<?php echo $user['price_charged']; ?>" required>

      <label for="cv">Upload CV (PDF only)</label>
      <input type="file" name="cv" accept=".pdf">

      <label for="photo">Upload Photo</label>
      <input type="file" name="photo" accept="image/*">

      <button type="submit">Update Profile</button>
    </form>
  </div>
</body>
</html>
