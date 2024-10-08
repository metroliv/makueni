<?php
// Include database connection
include('includes/config.php');

// Query to fetch all asset types
$assetTypeQuery = "SELECT id, asset_type FROM asset_types";
$assetTypesResult = $conn->query($assetTypeQuery);

// Fetch household names and IDs
$householdQuery = "SELECT household_id, household_name FROM households";
$householdResult = $conn->query($householdQuery);

// Initialize arrays to hold data
$assetTypes = [];
$households = [];

// Store asset types
while ($row = $assetTypesResult->fetch_assoc()) {
    $assetTypes[$row['id']] = $row['asset_type'];
}

// Store households
while ($row = $householdResult->fetch_assoc()) {
    $households[$row['household_id']] = $row['household_name'];
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get input values
    $householdId = (int)$_POST['household_id']; // From dropdown
    $assetTypeId = (int)$_POST['asset_type_id'];
    $assetValue = trim($_POST['asset_value']);

    // Validation and insertion logic
    if (!is_numeric($assetValue) || $assetValue <= 0 || $assetValue > 100000) {
        $errorMessage = "Asset value must be a positive number within 1 - 100,000.";
    } elseif ($householdId <= 0) {
        $errorMessage = "Please select a valid household.";
    } elseif ($assetTypeId <= 0) {
        $errorMessage = "Please select a valid asset type.";
    } else {
        // Insert asset into the database
        $insertQuery = $conn->prepare("INSERT INTO household_assets (household_id, asset_type_id, asset_value) VALUES (?, ?, ?)");
        $insertQuery->bind_param("iid", $householdId, $assetTypeId, $assetValue);

        if ($insertQuery->execute()) {
            $successMessage = "Household asset added successfully!";
            header("Location: assets.php");
            exit();
        } else {
            $errorMessage = "Error adding household asset: " . $insertQuery->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Household Asset</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Add Household Asset</h1>

    <?php if (!empty($errorMessage)) : ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <?php if (!empty($successMessage)) : ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="household_id">Select Household Name:</label>
            <select id="household_id" name="household_id" class="form-control" required>
                <option value="">-- Choose a household --</option>
                <?php foreach ($households as $id => $name) : ?>
                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="asset_type_id">Select Asset Type:</label>
            <select id="asset_type_id" name="asset_type_id" class="form-control" required>
                <option value="">-- Choose an asset type --</option>
                <?php foreach ($assetTypes as $id => $type) : ?>
                    <option value="<?php echo $id; ?>"><?php echo $type; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="asset_value">Enter Asset Value (1 - 100,000):</label>
            <input type="number" id="asset_value" name="asset_value" class="form-control" min="1" max="100000" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Asset</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
