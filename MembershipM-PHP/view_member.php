<?php
// Include database connection
include('includes/config.php');

// Get member ID from URL and validate it
$member_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($member_id === null) {
    die("Error: ID not specified.");
}

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM members_house WHERE Columnid = ?");
$stmt->bind_param("i", $member_id); // 'i' denotes that the parameter is an integer
$stmt->execute();
$memberResult = $stmt->get_result();

// Fetch member details
$member = $memberResult->fetch_assoc();

if (!$member) {
    echo "Member not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Member</title>
</head>
<body>
    <div class="container">
        <h1>Member Details</h1>
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($member['Columnfullname']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($member['Columnemail']); ?></p>
        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($member['Columncontact_number']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($member['address']); ?></p>
        <p><strong>DOB:</strong> <?php echo htmlspecialchars($member['Columndob']); ?></p>
        <p><strong>Occupation:</strong> <?php echo htmlspecialchars($member['Columnoccupation']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($member['Columngender']); ?></p>
        <p><strong>Membership Number:</strong> <?php echo htmlspecialchars($member['Columnmembership_number']); ?></p>
        <p><strong>Membership Type:</strong> <?php echo htmlspecialchars($member['Columnmembership_type']); ?></p>
        <p><strong>Country:</strong> <?php echo htmlspecialchars($member['Columncountry']); ?></p>
        <p><strong>Postcode:</strong> <?php echo htmlspecialchars($member['Columnpostcode']); ?></p>
        <p><strong>Expiry Date:</strong> <?php echo htmlspecialchars($member['Columnexpiry_date'] ?? 'N/A'); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($member['Columncreated_at']); ?></p>

        <a href="members_list.php">Back to Members List</a>
    </div>
</body>
</html>
