<?php
// Include database connection
include('includes/config.php');

// Initialize variables for user feedback
$errorMessages = [];
$successMessage = '';

// Fetch current system settings
$settingsQuery = "SELECT * FROM system_settings LIMIT 1";
$settingsResult = $conn->query($settingsQuery);
$currentSettings = $settingsResult->fetch_assoc();

// Default values in case the query returns null
if (!$currentSettings) {
    $currentSettings = [
        'site_name' => '',
        'site_email' => '',
        'site_contact' => '',
        'maintenance_mode' => 0,
    ];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $site_name = trim($_POST['site_name']);
    $site_email = trim($_POST['site_email']);
    $site_contact = trim($_POST['site_contact']);
    $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;

    // Validate inputs
    if (empty($site_name) || empty($site_email) || empty($site_contact)) {
        $errorMessages[] = "All fields are required.";
    }

    // Validate email format
    if (!filter_var($site_email, FILTER_VALIDATE_EMAIL)) {
        $errorMessages[] = "Invalid email format.";
    }

    // Only proceed if there are no validation errors
    if (empty($errorMessages)) {
        // Prepare a parameterized query to update system settings
        $updateQuery = $conn->prepare("UPDATE system_settings SET site_name = ?, site_email = ?, site_contact = ?, maintenance_mode = ? WHERE id = 1");
        $updateQuery->bind_param("ssii", $site_name, $site_email, $site_contact, $maintenance_mode);

        if ($updateQuery->execute()) {
            $successMessage = "System settings updated successfully!";
        } else {
            $errorMessages[] = "Error updating settings: " . $updateQuery->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">System Settings</h1>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errorMessages)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errorMessages as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-light p-4 rounded shadow-sm">
        <div class="form-group">
            <label for="site_name">Site Name:</label>
            <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($currentSettings['site_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="site_email">Site Email:</label>
            <input type="email" name="site_email" class="form-control" value="<?php echo htmlspecialchars($currentSettings['site_email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="site_contact">Site Contact:</label>
            <input type="text" name="site_contact" class="form-control" value="<?php echo htmlspecialchars($currentSettings['site_contact']); ?>" required>
        </div>
        <div class="form-group">
            <label for="maintenance_mode">Maintenance Mode:</label>
            <input type="checkbox" name="maintenance_mode" <?php echo $currentSettings['maintenance_mode'] ? 'checked' : ''; ?>>
            <small class="form-text text-muted">Check to enable maintenance mode.</small>
        </div>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
    <a href="dashboard.php" class="btn btn-link mt-3">Back to Dashboard</a>
</div>

</body>
</html>
