<?php
// Include database connection
include('includes/config.php');

// Fetch documents from the database
$query = "SELECT * FROM documents ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Documents</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<?php include('includes/header.php');?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php include('includes/nav.php');?>

 <?php include('includes/sidebar.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<div class="container mt-5">
    <h1 class="text-center mb-4">Uploaded Documents</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Document Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Uploaded At</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['document_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['document_description']); ?></td>
                    <td><?php echo htmlspecialchars($row['document_category']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($row['document_path']); ?>" class="btn btn-success btn-sm" download>Download</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">No documents uploaded yet.</div>
    <?php endif; ?>
    <a href="upload_document.php" class="btn btn-link mt-3">Upload New Document</a>
</div>
  </div></div>
</body>
</html>
