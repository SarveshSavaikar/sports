<?php
session_start();

// Check if OTP is set in session
if (isset($_SESSION['otp'])) {
    $generated_otp = $_SESSION['otp']; // Store the generated OTP in session for comparison
}

// Process the OTP verification
if (isset($_POST['otp'])) {
    $user_otp = $_POST['otp'];

    // Check if OTP matches
    if ($user_otp == $generated_otp) {
        echo "OTP Verified. Registration Complete!";
        // Proceed with registration or redirect to a different page
        unset($_SESSION['otp']);  // Clear OTP from session after successful verification
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>

<!-- HTML form to enter OTP -->
<form method="post" action="">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <input type="submit" value="Verify OTP">
</form>
