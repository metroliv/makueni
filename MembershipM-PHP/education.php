<?php
// Include database connection
include('includes/config.php');



// Check for success message
$successMessage = "";
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after displaying it
}

// Fetch education levels along with household members
$query = "
    SELECT 
        h.household_name, 
        hm.member_id, 
        hm.member_name, 
        el.education_level 
    FROM 
        households h
    JOIN 
        household_members hm ON h.household_id = hm.household_id
    LEFT JOIN 
        education_levels el ON hm.member_id = el.member_id"; // Adjusted query for better fetching

$result = $conn->query($query);


if (!$result) {
    // Handle query error
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Education Details</h1>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>

    <!-- Display education levels -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Household Name</th>
                <th>Member ID</th>
                <th>Member Name</th>
                <th>Education Level</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['household_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['member_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['education_level']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No education details found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
