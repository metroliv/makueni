
<?php
// Include database connection
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Trim and sanitize inputs
    $household_id = trim($_POST['household_id']);
    $member_id = trim($_POST['member_id']);
    $education_level = trim($_POST['education_level']);

    // Prepare a parameterized query to prevent SQL injection
    $insertQuery = $conn->prepare("INSERT INTO education_levels (household_id, member_id, education_level) VALUES (?, ?, ?)");
    $insertQuery->bind_param("iis", $household_id, $member_id, $education_level);

    if ($insertQuery->execute()) {
        echo "Education level added successfully!";
    } else {
        echo "Error adding education level: " . $insertQuery->error;
    }

    $insertQuery->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Education Level</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Add Education Levels</h1>
    <form method="POST" action="insert_education.php">
        <div class="form-group">
            <label for="household_id">Household ID:</label>
            <input type="number" name="household_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="member_id">Member ID:</label>
            <input type="number" name="member_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="education_level">Education Level:</label>
            <select name="education_level" class="form-control" required>
                <option value="Pre-Primary">Pre-Primary</option>
                <option value="Primary">Primary</option>
                <option value="Secondary">Secondary</option>
                <option value="Tertiary">Tertiary</option>
                <option value="Vocational">Vocational</option>
                <option value="None">None</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Education Level</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
