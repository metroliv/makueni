<?php
// Include database connection
include('includes/config.php');
// Check if the export button was clicked
if (isset($_POST['export'])) {
    // Fetch development programs from the database
    $exportQuery = "SELECT program_name, description, target_household_id, budget_amount, status FROM development_programs";
    $exportResult = $conn->query($exportQuery);

    // Prepare the CSV file for download
    if ($exportResult && $exportResult->num_rows > 0) {
        // Set the headers for the CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="development_programs.csv"');

        // Open the output stream
        $output = fopen('php://output', 'w');

        // Add the column headers
        fputcsv($output, ['Program Name', 'Description', 'Target Household ID', 'Budget Amount', 'Status']);

        // Fetch and write each row to the CSV
        while ($row = $exportResult->fetch_assoc()) {
            fputcsv($output, $row);
        }

        // Close the output stream
        fclose($output);
        exit(); // Exit to ensure no further output is sent
    } else {
        // Handle the case when there are no records to export
        $errorMessage = "No programs available for export.";
    }
}


// Check if session is already started to avoid session_start() errors
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check for success message
$successMessage = "";
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after displaying it
}

// Query to fetch development programs
$programsQuery = "
    SELECT dp.id AS program_id, dp.program_name, dp.description, 
           dp.budget_amount, dp.status, h.household_name 
    FROM development_programs dp 
    JOIN households h ON dp.target_household_id = h.household_id
";
$programsResult = $conn->query($programsQuery);

// Function to handle CSV export
if (isset($_POST['export'])) {
    // ... (CSV export code)
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Development Programs</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .btn {
            margin: 5px;
        }
        .status-badge {
            font-size: 0.9em;
            border-radius: 10px;
            padding: 5px 10px;
            color: #fff;
        }
        .status-active {
            background-color: #28a745; /* Green */
        }
        .status-completed {
            background-color: #007bff; /* Blue */
        }
        .status-pending {
            background-color: #ffc107; /* Yellow */
        }
        .status-cancelled {
            background-color: #dc3545; /* Red */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Development Programs</h1>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <div class="text-right mb-3">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addProgramModal">Add Program</button>
        <form method="POST" class="d-inline">
            <button type="submit" name="export" class="btn btn-success">Export to CSV</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Program ID</th>
                    <th>Program Name</th>
                    <th>Description</th>
                    <th>Target Household</th>
                    <th>Budget Amount (in $)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($programsResult->num_rows > 0): ?>
                    <?php while ($row = $programsResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['program_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['program_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['household_name']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['budget_amount'], 2)); ?></td>
                            <td>
                                <span class="status-badge 
                                    <?php 
                                        switch ($row['status']) {
                                            case 'Active':
                                                echo 'status-active';
                                                break;
                                            case 'Completed':
                                                echo 'status-completed';
                                                break;
                                            case 'Pending':
                                                echo 'status-pending';
                                                break;
                                            case 'Cancelled':
                                                echo 'status-cancelled';
                                                break;
                                        }
                                    ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No development programs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="addProgramModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProgramModalLabel">Add Development Program</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="add_program.php"> <!-- Assuming you have an add_program.php to handle submission -->
                    <div class="form-group">
                        <label for="program_name">Program Name</label>
                        <input type="text" class="form-control" id="program_name" name="program_name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="budget_amount">Budget Amount</label>
                        <input type="number" class="form-control" id="budget_amount" name="budget_amount" required>
                    </div>
                    <div class="form-group">
                        <label for="target_household_id">Target Household</label>
                        <select class="form-control" id="target_household_id" name="target_household_id" required>
                            <?php
                            $householdQuery = "SELECT household_id, household_name FROM households";
                            $householdResult = $conn->query($householdQuery);
                            while ($householdRow = $householdResult->fetch_assoc()): ?>
                                <option value="<?php echo $householdRow['household_id']; ?>"><?php echo htmlspecialchars($householdRow['household_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Active">Active</option>
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Program</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
