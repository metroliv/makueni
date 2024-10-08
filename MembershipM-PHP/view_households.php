<?php
// Include database connection
include('includes/config.php');

// Query to fetch all households
$householdQuery = "SELECT * FROM households";
$householdResult = $conn->query($householdQuery);

// Function to export data to CSV
function exportToCSV($data) {
    $filename = "households_" . date("Ymd") . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Output column headings
    fputcsv($output, ['Household ID', 'Household Name', 'Head of Household', 'Address', 'Contact Number', 'Email']);

    // Output data rows
    while ($row = $data->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// Check if export is requested
if (isset($_POST['export'])) {
    exportToCSV($householdResult);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Household List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .btn-success {
            margin-bottom: 15px;
        }
        .action-btns a {
            margin-right: 10px;
        }
    </style>
</head>
<?php include('includes/header.php');?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php include('includes/nav.php');?>

 <?php include('includes/sidebar.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
  
    <div class="container">
        <h1 class="text-center">Household List</h1>
        
        <form method="POST" class="text-center">
            <button type="submit" name="export" class="btn btn-success">Export to CSV</button>
        </form>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Household ID</th>
                            <th>Household Name</th>
                            <th>Head of Household</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($household = $householdResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($household['household_id']); ?></td>
                            <td><?php echo htmlspecialchars($household['household_name']); ?></td>
                            <td><?php echo htmlspecialchars($household['head_of_household']); ?></td>
                            <td><?php echo htmlspecialchars($household['address']); ?></td>
                            <td><?php echo htmlspecialchars($household['contact_number']); ?></td>
                            <td><?php echo htmlspecialchars($household['email']); ?></td>
                            <td class="action-btns">
                                <a href="edit_household.php?id=<?php echo $household['household_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_household.php?id=<?php echo $household['household_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this household?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="add_household.php" class="btn btn-primary mt-3">Add New Household</a>
    </div>

    <!-- Include Bootstrap JS for functionality -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    </div>
 
 </div>
 
 <?php include('includes/footer.php');?>
 </body>
 </html>
