<?php
include('includes/config.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$response = array('success' => false, 'message' => '');

// Fetch membership types for the dropdown
$membershipTypesQuery = "SELECT id, type, amount FROM membership_types";
$membershipTypesResult = $conn->query($membershipTypesQuery);

function generateUniqueFileName($originalName) {
    $timestamp = time();
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    return $timestamp . '_' . uniqid() . '.' . $extension;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Processing member registration
    if (isset($_POST['fullname'])) {
        $fullname = $_POST['fullname'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $contactNumber = $_POST['contactNumber'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $country = $_POST['country'];
        $postcode = $_POST['postcode'];
        $occupation = $_POST['occupation'];
        $membershipType = $_POST['membershipType'];

        $membershipNumber = 'CA-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        if (!empty($_FILES['photo']['name'])) {
            $uploadedPhoto = $_FILES['photo'];
            $uniquePhotoName = generateUniqueFileName($uploadedPhoto['name']);
            move_uploaded_file($uploadedPhoto['tmp_name'], 'uploads/member_photos/' . $uniquePhotoName);
        } else {
            $uniquePhotoName = 'default.jpg';
        }

        $insertQuery = "INSERT INTO members (fullname, dob, gender, contact_number, email, address, country, postcode, occupation, 
                        membership_type, membership_number, photo, created_at) 
                        VALUES ('$fullname', '$dob', '$gender', '$contactNumber', '$email', '$address', '$country', '$postcode', '$occupation', 
                                '$membershipType', '$membershipNumber', '$uniquePhotoName', NOW())";

        if ($conn->query($insertQuery) === TRUE) {
            $response['success'] = true;
            $response['message'] = 'Member added successfully! Membership Number: ' . $membershipNumber;
        } else {
            $response['message'] = 'Error: ' . $conn->error;
        }
    }

    // Processing family registration
    if (isset($_POST['familyName'])) {
        $familyName = $_POST['familyName'];
        $numMembers = $_POST['numMembers'];
        $financialStatus = $_POST['financialStatus'];
        $village = $_POST['village'];
        $subLocation = $_POST['subLocation'];
        $location = $_POST['location'];
        $subCounty = $_POST['subCounty'];
        $wards = $_POST['wards'];

        $insertFamilyQuery = "INSERT INTO families (family_name, num_members, financial_status, village, sub_location, location, sub_county, wards, created_at)
                              VALUES ('$familyName', '$numMembers', '$financialStatus', '$village', '$subLocation', '$location', '$subCounty', '$wards', NOW())";

        if ($conn->query($insertFamilyQuery) === TRUE) {
            $response['success'] = true;
            $response['message'] = 'Family registered successfully!';
        } else {
            $response['message'] = 'Error: ' . $conn->error;
        }
    }
}

?>

<?php include('includes/header.php'); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
    <?php include('includes/nav.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <div class="content-wrapper">
        <?php include('includes/pagetitle.php'); ?>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($response['success']): ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Success</h5>
                                <?php echo $response['message']; ?>
                            </div>
                        <?php elseif (!empty($response['message'])): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-ban"></i> Error</h5>
                                <?php echo $response['message']; ?>
                            </div>
                        <?php endif; ?>

                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-keyboard"></i> Add Members Form</h3>
                            </div>

                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="card-body">
                                    <!-- Existing Member Registration Fields -->
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="fullname">Full Name</label>
                                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter full name" required>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="dob">Date of Birth</label>
                                            <input type="date" class="form-control" id="dob" name="dob" required>
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-6">
                                            <label for="contactNumber">Contact Number</label>
                                            <input type="tel" class="form-control" id="contactNumber" name="contactNumber" placeholder="Enter contact number" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-6">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control" id="country" name="country" placeholder="Enter country" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-6">
                                            <label for="postcode">Postcode</label>
                                            <input type="text" class="form-control" id="postcode" name="postcode" placeholder="Enter postcode" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="occupation">Occupation</label>
                                            <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Enter occupation" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-6">
                                            <label for="membershipType">Membership Type</label>
                                            <select class="form-control" id="membershipType" name="membershipType" required>
                                                <?php
                                                if ($membershipTypesResult) {
                                                    while ($row = $membershipTypesResult->fetch_assoc()) {
                                                        $currencyQuery = "SELECT currency FROM settings";
                                                        $currencyResult = $conn->query($currencyQuery);
                                                        $currencyRow = $currencyResult->fetch_assoc();
                                                        $currencySymbol = $currencyRow['currency'] ?? '$';
                                                        echo "<option value='{$row['id']}'>{$row['type']} - {$currencySymbol}{$row['amount']}</option>";
                                                    }
                                                } else {
                                                    echo "Error: " . $conn->error;
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="photo">Member Photo</label>
                                            <input type="file" class="form-control-file" id="photo" name="photo">
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>

                        <!-- Family Registration Form -->
                        <div class="form-container">
                            <h2 class="text-center">Family Registration Form</h2>
                            <form method="post" action="">
                                <div class="form-step active" id="step1">
                                    <h4>Step 1: General Family Information</h4>
                                    <div class="form-group">
                                        <label for="familyName">Family Name</label>
                                        <input type="text" class="form-control" id="familyName" name="familyName" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="numMembers">Number of Members</label>
                                        <input type="number" class="form-control" id="numMembers" name="numMembers" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="financialStatus">Financial Status</label>
                                        <input type="text" class="form-control" id="financialStatus" name="financialStatus" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="village">Village</label>
                                        <input type="text" class="form-control" id="village" name="village" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="subLocation">Sub Location</label>
                                        <input type="text" class="form-control" id="subLocation" name="subLocation" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="subCounty">Sub County</label>
                                        <input type="text" class="form-control" id="subCounty" name="subCounty" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="wards">Wards</label>
                                        <input type="text" class="form-control" id="wards" name="wards" required>
                                    </div>
                                    <div class="col-sm-6">
                                            <label for="photo">Family</label>
                                            <input type="file" class="form-control-file" id="photo" name="photo">
                                        </div>
                                    <button type="submit" class="btn btn-primary">Submit Family Info</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
