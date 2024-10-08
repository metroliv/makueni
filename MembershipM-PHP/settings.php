<?php
include('includes/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateSettings'])) {
    $systemName = $_POST['systemName'];
    $currency = $_POST['currency'];

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logoName = $_FILES['logo']['name'];
        $logoTmpName = $_FILES['logo']['tmp_name'];
        $logoType = $_FILES['logo']['type'];
        $uploadPath = 'uploads/'; 

        $targetPath = $uploadPath . $logoName;
        if (move_uploaded_file($logoTmpName, $targetPath)) {
            $updateSettingsQuery = "UPDATE settings SET system_name = '$systemName', logo = '$targetPath', currency = '$currency' WHERE id = 1";
            $updateSettingsResult = $conn->query($updateSettingsQuery);

            if ($updateSettingsResult) {
                $successMessage = 'System settings updated successfully.';
            } else {
                $errorMessage = 'Error updating system settings: ' . $conn->error;
            }
        } else {
            $errorMessage = 'Error moving uploaded file.';
        }
    } else {
        $updateSettingsQuery = "UPDATE settings SET system_name = '$systemName', currency = '$currency' WHERE id = 1";
        $updateSettingsResult = $conn->query($updateSettingsQuery);
        if ($updateSettingsResult) {
            $successMessage = 'System settings updated successfully.';
        } else {
            $errorMessage = 'Error updating system settings: ' . $conn->error;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    $userId = $_SESSION['user_id'];
    $validatePasswordQuery = "SELECT password FROM users WHERE id = $userId";
    $validatePasswordResult = $conn->query($validatePasswordQuery);

    if ($validatePasswordResult->num_rows > 0) {
        $row = $validatePasswordResult->fetch_assoc();
        $hashedPassword = $row['password'];

        if (md5($currentPassword) === $hashedPassword) {
            $hashedNewPassword = md5($newPassword);
            $updatePasswordQuery = "UPDATE users SET password = '$hashedNewPassword' WHERE id = $userId";
            $updatePasswordResult = $conn->query($updatePasswordQuery);

            if ($updatePasswordResult) {
                $successMessagePassword = 'Password updated successfully.';
            } else {
                $errorMessagePassword = 'Error updating password: ' . $conn->error;
            }
        } else {
            $errorMessagePassword = 'Current password is incorrect.';
        }
    }
}

// New Password Reset Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resetPassword'])) {
    $email = $_POST['email'];
    
    // Verify email exists in users table
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);
    
    if ($checkEmailResult->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50)); // Create a token
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Set token expiry
        
        // Insert token into a password reset table (create this table if not exists)
        $insertTokenQuery = "INSERT INTO password_resets (email, token, expiry) VALUES ('$email', '$token', '$expiry')";
        if ($conn->query($insertTokenQuery)) {
            // Send reset email logic goes here
            $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
            // Send email logic (use mail() function or PHPMailer)

            $successMessageReset = 'Password reset link has been sent to your email.';
        } else {
            $errorMessageReset = 'Error creating reset token.';
        }
    } else {
        $errorMessageReset = 'Email not found.';
    }
}

$fetchSettingsQuery = "SELECT * FROM settings WHERE id = 1";
$fetchSettingsResult = $conn->query($fetchSettingsQuery);

if ($fetchSettingsResult->num_rows > 0) {
    $settings = $fetchSettingsResult->fetch_assoc();
}
?>

<?php include('includes/header.php');?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
    <?php include('includes/nav.php');?>
    <?php include('includes/sidebar.php');?>

    <div class="content-wrapper">
        <?php include('includes/pagetitle.php');?>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-cogs"></i> System Settings</h3>
                            </div>

                            <?php
                            // Display messages
                            if (!empty($successMessage)) {
                                echo '<div class="alert alert-success">' . $successMessage . '</div>';
                            } elseif (!empty($errorMessage)) {
                                echo '<div class="alert alert-danger">' . $errorMessage . '</div>';
                            }

                            if (!empty($successMessagePassword)) {
                                echo '<div class="alert alert-success">' . $successMessagePassword . '</div>';
                            } elseif (!empty($errorMessagePassword)) {
                                echo '<div class="alert alert-danger">' . $errorMessagePassword . '</div>';
                            }

                            if (!empty($successMessageReset)) {
                                echo '<div class="alert alert-success">' . $successMessageReset . '</div>';
                            } elseif (!empty($errorMessageReset)) {
                                echo '<div class="alert alert-danger">' . $errorMessageReset . '</div>';
                            }
                            ?>

                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="systemName">System Name:</label>
                                        <input type="text" id="systemName" name="systemName" class="form-control"
                                            value="<?php echo isset($settings['system_name']) ? $settings['system_name'] : ''; ?>"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="logo">Logo:</label>
                                        <input type="file" id="logo" name="logo" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="currency">Currency:</label>
                                        <input type="text" id="currency" name="currency" class="form-control"
                                            value="<?php echo isset($settings['currency']) ? $settings['currency'] : ''; ?>"
                                            required>
                                    </div>

                                    <button type="submit" name="updateSettings" class="btn btn-primary">Update Settings</button>
                                </div>
                            </form>

                            <form method="post" action="">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="currentPassword">Current Password:</label>
                                        <input type="password" id="currentPassword" name="currentPassword" class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="newPassword">New Password:</label>
                                        <input type="password" id="newPassword" name="newPassword" class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="confirmPassword">Confirm Password:</label>
                                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control"
                                            required>
                                    </div>

                                    <button type="submit" name="changePassword" class="btn btn-primary">Change Password</button>
                                </div>
                            </form>

                            <!-- Password Reset Form -->
                            <form method="post" action="">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="email">Enter your email for password reset:</label>
                                        <input type="email" id="email" name="email" class="form-control" required>
                                    </div>

                                    <button type="submit" name="resetPassword" class="btn btn-primary">Reset Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <aside class="control-sidebar control-sidebar-dark">
    </aside>

    <footer class="main-footer">
        <strong> &copy; <?php echo date('Y');?> codeastro.com -</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Developed By</b> <a href="https://codeastro.com/">CodeAstro</a>
        </div>
    </footer>
</div>

<?php include('includes/footer.php');?>
</body>
</html>
