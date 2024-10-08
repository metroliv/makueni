<?php
// Include database connection
include('includes/config.php');

// Set pagination variables
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Handle search functionality
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare the SQL query for fetching asset data
$assetQuery = "
    SELECT h.household_id, h.household_name, 
           ha.asset_type, COUNT(*) AS asset_count, 
           SUM(ha.asset_value) AS total_value, 
           AVG(ha.asset_value) AS avg_value 
    FROM households h
    LEFT JOIN household_assets ha ON h.household_id = ha.household_id
";

// Apply search filter if a search term is provided
if (!empty($searchTerm)) {
    $assetQuery .= " WHERE h.household_name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
}

$assetQuery .= " GROUP BY h.household_id, ha.asset_type
                 ORDER BY h.household_name, ha.asset_type
                 LIMIT $limit OFFSET $offset";

$assetResult = $conn->query($assetQuery);

// Fetch total records for pagination
$totalQuery = "
    SELECT COUNT(DISTINCT h.household_id) AS total 
    FROM households h
    LEFT JOIN household_assets ha ON h.household_id = ha.household_id
";

if (!empty($searchTerm)) {
    $totalQuery .= " WHERE h.household_name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
}

$totalResult = $conn->query($totalQuery);
$totalCount = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalCount / $limit);

// Arrays to hold data for the chart
$households = [];
$assetTypes = [];
$assetData = [];

// Fetch results
while ($row = $assetResult->fetch_assoc()) {
    $householdId = $row['household_id'];
    $householdName = $row['household_name'];
    $assetType = $row['asset_type'];

    // Initialize household entry if not already done
    if (!isset($households[$householdId])) {
        $households[$householdId] = $householdName;
    }

    // Store asset data
    if (!isset($assetData[$householdId])) {
        $assetData[$householdId] = [];
    }
    $assetData[$householdId][$assetType] = [
        'asset_count' => $row['asset_count'],
        'total_value' => $row['total_value'],
        'avg_value' => $row['avg_value'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Household Assets Overview</title>

    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        .card {
            margin: 10px;
        }
        .card-header {
            font-size: 20px;
        }
        .table {
            margin-top: 20px;
        }
        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Household Assets Overview</h1>

    <!-- Search Form -->
    <form method="GET" class="form-inline mb-3">
        <input type="text" name="search" class="form-control mr-2" placeholder="Search by household name" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="assets.php" class="btn btn-secondary ml-2">Reset</a>
    </form>

    <!-- Household Assets Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Household Name</th>
                    <th>Asset Type</th>
                    <th>Asset Count</th>
                    <th>Total Asset Value (in $)</th>
                    <th>Average Asset Value (in $)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($households as $householdId => $householdName): ?>
                    <?php if (isset($assetData[$householdId])): ?>
                        <?php foreach ($assetData[$householdId] as $assetType => $data): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($householdName); ?></td>
                                <td><?php echo htmlspecialchars($assetType); ?></td>
                                <td><?php echo htmlspecialchars($data['asset_count']); ?></td>
                                <td><?php echo htmlspecialchars(number_format($data['total_value'], 2)); ?></td>
                                <td><?php echo htmlspecialchars(number_format($data['avg_value'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td><?php echo htmlspecialchars($householdName); ?></td>
                            <td colspan="4">No assets recorded.</td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Previous</a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Export Button -->
    <div class="text-right">
        <a href="export_assets.php?search=<?php echo urlencode($searchTerm); ?>" class="btn btn-success mb-3">Export to CSV</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
