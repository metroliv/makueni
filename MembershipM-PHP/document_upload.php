<?php
// Include database connection
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $document_name = trim($_POST['document_name']);
    $document_description = trim($_POST['document_description']);
    $document_category = trim($_POST['document_category']);
    $document_file = $_FILES['document_file'];

    // Validate file upload
    if ($document_file['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Ensure this directory exists and is writable
        $uploadFile = $uploadDir . basename($document_file['name']);

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($document_file['tmp_name'], $uploadFile)) {
            // Prepare a parameterized query to insert document information
            $insertQuery = $conn->prepare("INSERT INTO documents (document_name, document_description, document_category, document_path) VALUES (?, ?, ?, ?)");
            $insertQuery->bind_param("ssss", $document_name, $document_description, $document_category, $uploadFile);

            if ($insertQuery->execute()) {
                echo "<div class='alert alert-success'>Document uploaded successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error uploading document: " . $insertQuery->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Failed to move uploaded file.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error uploading file: " . $document_file['error'] . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Upload</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Upload Document</h1>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="document_name">Document Name:</label>
            <input type="text" name="document_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="document_description">Document Description:</label>
            <textarea name="document_description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="document_category">Document Category:</label>
            <input type="text" name="document_category" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="document_file">Select Document to Upload:</label>
            <input type="file" name="document_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Document</button>
    </form>
    <a href="uploaded_documents.php" class="btn btn-link mt-3">View Uploaded Documents</a>
</div>

</body>
</html>
