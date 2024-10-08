
<?php
require_once('includes/head.php');
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $username = $_POST['username'];
        $phone = $_POST['phone'];

        if (empty($email) || empty($password) || empty($username) || empty($phone)) {
            $error_message = "All fields are required!";
        } elseif ($password !== $confirmPassword) {
            $error_message = "Passwords do not match!";
        } else {
            // Check if email already exists
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $error_message = "This email is already registered!";
            } else {
                // Email is not registered, proceed with registration
                $hashed_password = md5($password);  // You should use a more secure hashing mechanism like password_hash()

                $sql = "INSERT INTO users (username, email, password, phone) 
                        VALUES ('$username', '$email', '$hashed_password', '$phone')";

                if ($conn->query($sql) === TRUE) {
                    $success_message = "Registration successful! You can now log in.";
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Create Your Account</h4>
                    </div>
                    <div class="card-body">

                        <!-- Display Success or Error Message -->
                        <?php
                            if (isset($error_message)) {
                                echo '<div class="alert alert-danger">' . $error_message . '</div>';
                            } elseif (isset($success_message)) {
                                echo '<div class="alert alert-success">' . $success_message . '</div>';
                            }
                        ?>

                        <form method="post" action="registration.php" novalidate>
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <div class="invalid-feedback">
                                    Please enter your username.
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number:</label>
                                <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" required>
                                <div class="invalid-feedback">
                                    Please enter a valid phone number (10 digits).
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback">
                                    Password is required.
                                </div>
                                <span class="position-absolute end-0 top-0 mt-2 me-2" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                </span>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                <div class="invalid-feedback">
                                    Please confirm your password.
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#">terms and conditions</a>.
                                </label>
                                <div class="invalid-feedback">
                                    You must agree to the terms before submitting.
                                </div>
                            </div>

                            <!-- Register Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="register">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password Visibility Toggle
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Bootstrap Form Validation
        (function () {
            'use strict';
            var forms = document.querySelectorAll('form');

            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
