<?php
// Include database connection
include('includes/config.php');

// Get household ID from URL
$household_id = $_GET['id'];

// Query to fetch household details
$householdQuery = "SELECT * FROM households WHERE household_id = $household_id";
$householdResult = $conn->query($householdQuery);
$household = $householdResult->fetch_assoc();

if (!$household) {
    echo "Household not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $household_name = $_POST['household_name'];
    $head_of_household = $_POST['head_of_household'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        $updateQuery = "UPDATE households SET household_name='$household_name', head_of_household='$head_of_household', address='$address', contact_number='$contact_number', email='$email' WHERE household_id=$household_id";
        
        if ($conn->query($updateQuery) === TRUE) {
            header("Location: view_households.php");
            exit();
        } else {
            echo "Error: " . $updateQuery . "<br>" . $conn->error;
        }
    }
}
?>

<?php include('includes/header.php');?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php include('includes/nav.php');?>

 <?php include('includes/sidebar.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    
    <div class="container mt-5">
        <h1 class="text-center mb-4">Edit Household</h1>
        <form method="POST">
            <div class="form-group">
                <label for="household_name">Household Name:</label>
                <input type="text" class="form-control" name="household_name" value="<?php echo htmlspecialchars($household['household_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="head_of_household">Head of Household:</label>
                <input type="text" class="form-control" name="head_of_household" value="<?php echo htmlspecialchars($household['head_of_household']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($household['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" class="form-control" name="contact_number" value="<?php echo htmlspecialchars($household['contact_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($household['email']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Household</button>
        </form>
        <a href="household_list.php" class="btn btn-secondary mt-3">Back to Household List</a>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</div>
 
 </div>
 
 <?php include('includes/footer.php');?>
 </body>
 </html>


