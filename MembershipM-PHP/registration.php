


<?php
// registration.php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die('Passwords do not match.');
    }

    // Hash the password
    $hashedPassword = md5($password); // Consider using password_hash() for better security

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (email, password, registration_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $email, $hashedPassword);

    // Execute the statement
    if ($stmt->execute()) {
        echo 'User registered successfully!';
        echo 'back to login';
    
    } else {
        echo 'Error: ' . $conn->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
<a href="index.php">login</a>