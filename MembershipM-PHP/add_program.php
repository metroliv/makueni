<?php
// Include database connection
include('includes/config.php');



// Initialize messages
$errorMessage = "";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get input values
    $programName = trim($_POST['program_name']);
    $description = trim($_POST['description']);
    $targetHouseholdId = (int)$_POST['target_household_id'];
    $budgetAmount = trim($_POST['budget_amount']);
    $status = trim($_POST['status']); // New status input

    // Validation logic
    if (empty($programName) || empty($description)) {
        $errorMessage = "Program name and description are required.";
    } elseif ($targetHouseholdId <= 0) {
        $errorMessage = "Please select a valid household.";
    } elseif (!is_numeric($budgetAmount) || $budgetAmount <= 0) {
        $errorMessage = "Budget amount must be a positive number.";
    } elseif (empty($status)) {
        $errorMessage = "Program status is required.";
    } else {
        // Insert development program into the database
        $insertQuery = $conn->prepare("INSERT INTO development_programs (program_name, description, target_household_id, budget_amount, status) VALUES (?, ?, ?, ?, ?)");
        $insertQuery->bind_param("ssdss", $programName, $description, $targetHouseholdId, $budgetAmount, $status);

        if ($insertQuery->execute()) {
            // Set success message in session
            $_SESSION['success_message'] = "Development program added successfully!";
            header("Location: programs.php"); // Redirect after successful submission
            exit();
        } else {
            $errorMessage = "Error adding development program: " . $insertQuery->error;
        }
    }
}

// Fetch households for dropdown
$householdQuery = "SELECT household_id, household_name FROM households";
$householdResult = $conn->query($householdQuery);
$households = [];
while ($row = $householdResult->fetch_assoc()) {
    $households[$row['household_id']] = $row['household_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Development Program</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Add Development Program</h1>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="program_name">Program Name:</label>
            <input type="text" id="program_name" name="program_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="target_household_id">Select Target Household:</label>
            <select id="target_household_id" name="target_household_id" class="form-control" required>
                <option value="">-- Choose a household --</option>
                <?php foreach ($households as $id => $name): ?>
                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="budget_amount">Budget Amount (in $):</label>
            <input type="number" id="budget_amount" name="budget_amount" class="form-control" min="1" required>
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select id="status" name="status" class="form-control" required>
                <option value="">-- Select Status --</option>
                <option value="Active">Active</option>
                <option value="Completed">Completed</option>
                <option value="Pending">Pending</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Add Program</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
