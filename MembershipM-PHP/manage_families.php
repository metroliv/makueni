<?php
// Query to get all registered families
$sql = "SELECT family_name, num_members, financial_status, village, image_path FROM families ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<div class="card-footer text-center">
    <a href="manage_families.php" class="uppercase">View All Families</a>
</div>

<div class="family-list">
    <?php
    // Check if there are results
    if ($result->num_rows > 0) {
        // Loop through the families and display their information
        while ($row = $result->fetch_assoc()) {
            echo '<div class="family-item">';
            echo '<img src="uploads/member_photos/' . htmlspecialchars($row['image_path']) . '" alt="Family Photo" class="family-photo" />';
            echo '<h3>' . htmlspecialchars($row['family_name']) . '</h3>';
            echo '<p>Number of Members: ' . htmlspecialchars($row['num_members']) . '</p>';
            echo '<p>Financial Status: ' . htmlspecialchars($row['financial_status']) . '</p>';
            echo '<p>Village: ' . htmlspecialchars($row['village']) . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No families registered yet.</p>';
    }
    ?>
</div>

<style>
    .family-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin: 20px 0;
    }
    
    .family-item {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 15px;
        margin: 10px;
        text-align: center;
        width: 200px; /* Adjust width as necessary */
    }

    .family-photo {
        width: 100%;
        height: auto;
        border-radius: 5px;
    }
</style>
<?php
// Include the database configuration file
include('includes/config.php'); // Adjust the path as necessary

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Your existing code...
$sql = "SELECT family_name, num_members, financial_status, village, image_path FROM families ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
