<?php
// Include database connection
include('includes/config.php');

// Handle search term
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare the SQL query for fetching asset data
$assetQuery = "
    SELECT h.household_name, 
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

$assetQuery .= " GROUP BY h.household_name, ha.asset_type
                 ORDER BY h.household_name, ha.asset_type";

$assetResult = $conn->query($assetQuery);

// Set headers for CSV export
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=household_assets.csv');

// Open output stream for CSV
$output = fopen('php://output', 'w');

// Write the header row to CSV
fputcsv($output, ['Household Name', 'Asset Type', 'Asset Count', 'Total Asset Value (in $)', 'Average Asset Value (in $)']);

// Write data rows to CSV
while ($row = $assetResult->fetch_assoc()) {
    fputcsv($output, [
        $row['household_name'],
        $row['asset_type'],
        $row['asset_count'],
        number_format($row['total_value'], 2),
        number_format($row['avg_value'], 2)
    ]);
}

// Close output stream
fclose($output);
exit();
?>
