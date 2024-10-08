<?php
// Include database connection
include('includes/config.php');

// Initialize variables for user feedback
$errorMessages = [];
$successMessage = '';

// Fetch education levels from the database
$educationLevelsQuery = "SELECT DISTINCT education_level FROM education_levels";
$educationLevelsResult = $conn->query($educationLevelsQuery);

$educationLevels = [];
if ($educationLevelsResult) {
    while ($row = $educationLevelsResult->fetch_assoc()) {
        $educationLevels[] = $row['education_level'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Trim and sanitize inputs for household
    $household_name = trim($_POST['household_name']);
    $head_of_household = trim($_POST['head_of_household']);
    $address = trim($_POST['address']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $gender = trim($_POST['gender']);

    // Validate inputs for household
    if (empty($household_name) || empty($head_of_household) || empty($address) || empty($contact_number) || empty($email) || empty($gender)) {
        $errorMessages[] = "All fields are required.";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessages[] = "Invalid email format.";
    }

    // Check if contact number is numeric (optional)
    if (!is_numeric($contact_number)) {
        $errorMessages[] = "Contact number must be numeric.";
    }

    // Only proceed if there are no validation errors
    if (empty($errorMessages)) {
        // Prepare a parameterized query to prevent SQL injection for household
        $insertQuery = $conn->prepare("INSERT INTO households (household_name, head_of_household, address, contact_number, email, gender) VALUES (?, ?, ?, ?, ?, ?)");
        $insertQuery->bind_param("ssssss", $household_name, $head_of_household, $address, $contact_number, $email, $gender);

        if ($insertQuery->execute()) {
            $successMessage = "Household added successfully!";

            // Get the last inserted household ID
            $householdId = $conn->insert_id;

            // Insert household members
            if (!empty($_POST['member_name']) && is_array($_POST['member_name'])) {
                foreach ($_POST['member_name'] as $index => $member_name) {
                    $member_gender = $_POST['member_gender'][$index];
                    $education_level = $_POST['education_level'][$index];

                    // Validate member inputs
                    if (!empty($member_name) && !empty($member_gender) && !empty($education_level)) {
                        // Insert member into household_members table
                        $insertMemberQuery = $conn->prepare("INSERT INTO household_members (household_id, member_name, gender, education_level) VALUES (?, ?, ?, ?)");
                        $insertMemberQuery->bind_param("isss", $householdId, $member_name, $member_gender, $education_level);
                        $insertMemberQuery->execute();
                    }
                }
            }

            // Redirect to view households or display success message
            header("Location: view_households.php");
            exit();
        } else {
            $errorMessages[] = "Error adding household: " . $insertQuery->error;
        }
    }
}

// Gender distribution for the chart (as in your original code)
$maleCountQuery = "SELECT COUNT(*) AS male_count FROM households WHERE gender = 'Male'";
$femaleCountQuery = "SELECT COUNT(*) AS female_count FROM households WHERE gender = 'Female'";

$maleResult = $conn->query($maleCountQuery);
$femaleResult = $conn->query($femaleCountQuery);

$maleCount = $maleResult->fetch_assoc()['male_count'];
$femaleCount = $femaleResult->fetch_assoc()['female_count'];
?>

<?php include('includes/header.php'); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Add Household</h1>

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
                <label for="household_name">Household Name:</label>
                <input type="text" name="household_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="head_of_household">Head of Household:</label>
                <input type="text" name="head_of_household" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" name="contact_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <!-- Member Information Section -->
            <h3>Add Household Members</h3>
            <div id="member-fields">
                <div class="member-form">
                    <div class="form-group">
                        <label for="member_name[]">Member Name:</label>
                        <input type="text" name="member_name[]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="member_gender[]">Member Gender:</label>
                        <select name="member_gender[]" class="form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="education_level[]">Education Level:</label>
                        <select name="education_level[]" class="form-control" required>
                            <option value="">Select Education Level</option>
                            <?php foreach ($educationLevels as $level): ?>
                                <option value="<?php echo htmlspecialchars($level); ?>"><?php echo htmlspecialchars($level); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" id="add-member">Add Another Member</button>
            <button type="submit" class="btn btn-primary btn-block">Add Household and Members</button>
        </form>
        <a href="view_households.php" class="btn btn-link mt-3">Back to Household List</a>
    </div>
  </div>
</div>

<!-- Include Bootstrap JS for functionality -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#add-member').click(function() {
            // Clone the first member form and append to member-fields
            var memberForm = $('.member-form:first').clone();
            memberForm.find('input').val(''); // Clear input values
            memberForm.find('select').val(''); // Reset select values
            $('#member-fields').append(memberForm);
        });
    });
</script>
</body>
</html>
