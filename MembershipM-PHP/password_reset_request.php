<?php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetRequest'])) {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50)); // Generate a secure token

    // Insert the token into the database
    $insertTokenQuery = "INSERT INTO password_resets (email, token) VALUES ('$email', '$token')";
    if ($conn->query($insertTokenQuery) === TRUE) {
        // Send email with the reset link
        $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: $resetLink";
        mail($email, $subject, $message); // Send email
        $successMessage = "Reset link sent to your email.";
    } else {
        $errorMessage = "Error: " . $conn->error;
    }
}
?>

<form method="post" action="">
    <label for="email">Enter your email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit" name="resetRequest">Request Password Reset</button>
    <?php if (!empty($successMessage)) echo $successMessage; ?>
    <?php if (!empty($errorMessage)) echo $errorMessage; ?>
</form>
