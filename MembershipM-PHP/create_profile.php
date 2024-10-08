<?php
session_start();



// Check if the family_id is set in the session
if (!isset($_SESSION['family_id'])) {
    echo "Family ID is not set. Please set the family ID to continue.";
    exit(); // Stop execution if family ID is not set
}

$familyId = "family_id"; // Get the family ID from the session

// Ensure the connection is established
if (!isset($conn)) {
    echo "Database connection not established. Please check the configuration.";
    exit(); // Stop execution if the connection is not set
}

// Example query to create a profile for the family
try {
    $familyQuery = "SELECT * FROM families WHERE id = ?";
    $stmt = $conn->prepare($familyQuery);
    $stmt->bind_param("i", $familyId);
    $stmt->execute();
    $familyResult = $stmt->get_result();

    if ($familyResult->num_rows > 0) {
        $family = $familyResult->fetch_assoc();
        // Process the family data as needed
    } else {
        echo "No family found with the given ID.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close(); // Close the connection if necessary
?>


<body>
    <main class="main" id="top">
        <div class="container-fluid">
            <!-- Sidebar -->
            <?php include('../partials/youth_sidenav.php'); ?>
        
            <!-- Main Content Area -->
            <div class="content">
                <!-- Header Section -->
                <?php include('../partials/header.php'); ?>

                <!-- Navigation Bar -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#">Government of Makueni - Family Management System</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <!-- Add any additional navigation items here if needed -->
                        </div>
                    </div>
                </nav>

                <!-- Content Section -->
                <div class="container mt-3">
                    <!-- Profile Header -->
                    <div class="profile-header bg-primary text-white text-center py-4 rounded">
                        <h1>Family Profile</h1>
                        <p>View and manage details for the selected family.</p>
                    </div>

                    <!-- Family Details -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <img src="imgs/family.jpeg" class="card-img-top" alt="Family">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($family['family_name']); ?></h5>
                                    <p class="card-text"><strong>Head of Family:</strong> <?php echo htmlspecialchars($family['head_of_family']); ?></p>
                                    <p class="card-text"><strong>Total Members:</strong> <?php echo count($members); ?></p>
                                    <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($family['location']); ?></p>
                                    <div class="card bg-light mb-3">
                                        <div class="card-body">                                        
                                            <p class="card-text"><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Family Members</h5>
                                    <ul class="list-group">
                                        <?php foreach ($members as $member): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?php echo htmlspecialchars($member['name']); ?> - Age <?php echo htmlspecialchars($member['age']); ?>
                                                <a href="?delete_id=<?php echo $member['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information -->
                    <div class="card mt-4">
                        <div class="card-header bg-success text-white">
                            <h5>Financial Information</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Total Income:</strong> Ksh <?php echo htmlspecialchars(number_format($financial['total_income'], 2)); ?></p>
                            <p><strong>Total Expenses:</strong> Ksh <?php echo htmlspecialchars(number_format($financial['total_expenses'], 2)); ?></p>
                            <p><strong>Net Balance:</strong> Ksh <?php echo htmlspecialchars(number_format($financial['total_income'] - $financial['total_expenses'], 2)); ?></p>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h5>Recent Activities</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">Received financial aid on August 1, 2024</li>
                                <li class="list-group-item">Participated in community outreach on July 15, 2024</li>
                                <li class="list-group-item">Completed education workshop on June 10, 2024</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Edit Family Details Button -->
                    <div class="mt-4 text-center">
                        <a href="edit-family.php" class="btn btn-warning btn-lg">Edit Family Details</a>
                    </div>
                </div>
            </div>
        </div>

        <?php include('includes/footer.php'); ?>
    </main>

    <!-- Bootstrap and Font Awesome -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/vnd.microsoft.icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</body>
