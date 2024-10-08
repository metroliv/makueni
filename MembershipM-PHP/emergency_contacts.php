<?php
// Include database connection
include('includes/config.php');

// Initialize variables for user feedback
$errorMessages = [];
$successMessage = '';

// Fetch current emergency contacts for a specific household (assumed household_id is passed via GET)
$householdId = $_GET['household_id'] ?? 0;
$contactsQuery = "SELECT * FROM emergency_contacts WHERE household_id = ?";
$contactsStmt = $conn->prepare($contactsQuery);
$contactsStmt->bind_param("i", $householdId);
$contactsStmt->execute();
$contactsResult = $contactsStmt->get_result();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $contact_name = trim($_POST['contact_name']);
    $relationship = trim($_POST['relationship']);
    $contact_number = trim($_POST['contact_number']);

    // Validate inputs
    if (empty($contact_name) || empty($relationship) || empty($contact_number)) {
        $errorMessages[] = "All fields are required.";
    }

    // Only proceed if there are no validation errors
    if (empty($errorMessages)) {
        // Prepare a parameterized query to insert the new emergency contact
        $insertQuery = $conn->prepare("INSERT INTO emergency_contacts (household_id, contact_name, relationship, contact_number) VALUES (?, ?, ?, ?)");
        $insertQuery->bind_param("isss", $householdId, $contact_name, $relationship, $contact_number);

        if ($insertQuery->execute()) {
            $successMessage = "Emergency contact added successfully!";
            // Optionally, refresh the contacts list
            $contactsStmt->execute();
            $contactsResult = $contactsStmt->get_result();
        } else {
            $errorMessages[] = "Error adding emergency contact: " . $insertQuery->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Contacts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Emergency Contacts for Household ID: <?php echo htmlspecialchars($householdId); ?></h1>

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
            <label for="contact_name">Contact Name:</label>
            <input type="text" name="contact_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="relationship">Relationship:</label>
            <input type="text" name="relationship" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="contact_number">Contact Number:</label>
            <input type="text" name="contact_number" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Emergency Contact</button>
    </form>

    <h3 class="mt-4">Existing Emergency Contacts</h3>
    <ul class="list-group">
        <?php while ($contact = $contactsResult->fetch_assoc()): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($contact['contact_name']); ?> - 
                <?php echo htmlspecialchars($contact['relationship']); ?> - 
                <?php echo htmlspecialchars($contact['contact_number']); ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <a href="view_households.php" class="btn btn-link mt-3">Back to Household List</a>
</div>

</body>
</html>
