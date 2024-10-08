<?php
// Include database connection
include('includes/config.php');

// Get household ID from URL and sanitize it
$household_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($household_id <= 0) {
    echo "Invalid household ID.";
    exit;
}

// Check if the household exists before trying to delete
$checkQuery = "SELECT * FROM households WHERE household_id = $household_id";
$checkResult = $conn->query($checkQuery);

if ($checkResult->num_rows === 0) {
    echo "Household not found.";
    exit;
}

// Confirm deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Query to delete the household
    $deleteQuery = "DELETE FROM households WHERE household_id = $household_id";

    if ($conn->query($deleteQuery) === TRUE) {
        header("Location: view_households.php");
        exit();
    } else {
        echo "Error deleting household: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Household</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Delete Household</h1>
        <div class="alert alert-warning">
            <strong>Warning!</strong> Are you sure you want to delete this household?
        </div>
        <form method="POST" class="text-center">
            <button type="submit" class="btn btn-danger">Yes, Delete Household</button>
            <a href="view_households.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
