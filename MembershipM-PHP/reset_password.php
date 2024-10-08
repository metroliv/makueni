<?php
include('includes/config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetPassword'])) {
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $hashedNewPassword = md5($newPassword);

            // Get email associated with the token
            $selectTokenQuery = "SELECT email FROM password_resets WHERE token='$token'";
            $result = $conn->query($selectTokenQuery);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $email = $row['email'];

                // Update user's password
                $updatePasswordQuery = "UPDATE users SET password='$hashedNewPassword' WHERE email='$email'";
                if ($conn->query($updatePasswordQuery) === TRUE) {
                    $successMessage = "Password reset successfully.";
                    // Optionally, delete the token from the database
                    $deleteTokenQuery = "DELETE FROM password_resets WHERE token='$token'";
                    $conn->query($deleteTokenQuery);
                } else {
                    $errorMessage = "Error updating password: " . $conn->error;
                }
            } else {
                $errorMessage = "Invalid token.";
            }
        } else {
            $errorMessage = "Passwords do not match.";
        }
    }
} else {
    $errorMessage = "No token provided.";
}
?>

<form method="post" action="">
    <label for="newPassword">New Password:</label>
    <input type="password" id="newPassword" name="newPassword" required>
    <label for="confirmPassword">Confirm Password:</label>
    <input type="password" id="confirmPassword" name="confirmPassword" required>
    <button type="submit" name="resetPassword">Reset Password</button>
    <?php if (!empty($successMessage)) echo $successMessage; ?>
    <?php if (!empty($errorMessage)) echo $errorMessage; ?>
</form>
