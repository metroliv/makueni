<?php
// Include database connection
include('includes/config.php');

// Pagination settings
$limit = 5; // Number of households to display per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query to fetch all households with pagination
$householdQuery = "SELECT * FROM households LIMIT $limit OFFSET $offset";
$householdResult = $conn->query($householdQuery);

// Query to get total number of households for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM households";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalHouseholds = $totalRow['total'];
$totalPages = ceil($totalHouseholds / $limit);
?>

<?php include('includes/header.php'); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
    <?php include('includes/nav.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container mt-5">
            <h1 class="text-center mb-4">Household List</h1>

            <div class="mb-3 d-flex justify-content-between">
                <a href="add_household.php" class="btn btn-primary">Add New Household</a>
                <div>
                    <form action="" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Search..." aria-label="Search">
                        <button type="submit" class="btn btn-outline-secondary">Search</button>
                    </form>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Household ID</th>
                        <th>Household Name</th>
                        <th>Head of Household</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($household = $householdResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $household['household_id']; ?></td>
                        <td><?php echo $household['household_name']; ?></td>
                        <td><?php echo $household['head_of_household']; ?></td>
                        <td>
                            <a href="edit_household.php?id=<?php echo $household['household_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_household.php?id=<?php echo $household['household_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="card-footer text-center">
        <a href="view_households.php" class="uppercase">View All Members</a>
    </div>
            <?php
            // Check if no households found
            if ($householdResult->num_rows === 0) {
                echo '<div class="alert alert-warning text-center">No households found.</div>';
            }
            ?>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
