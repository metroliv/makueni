<?php
// Include database connection
include('includes/config.php');

// Query to count male and female heads of households
$maleCountQuery = "SELECT COUNT(*) AS male_count FROM households WHERE gender = 'Male'";
$femaleCountQuery = "SELECT COUNT(*) AS female_count FROM households WHERE gender = 'Female'";

$maleResult = $conn->query($maleCountQuery);
$femaleResult = $conn->query($femaleCountQuery);

$maleCount = $maleResult->fetch_assoc()['male_count'];
$femaleCount = $femaleResult->fetch_assoc()['female_count'];

// Calculate the total number of households
$totalHouseholds = $maleCount + $femaleCount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gender Distribution of Heads of Household</title>

    <!-- Bootstrap CSS for Styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .card {
            margin: 10px;
        }
        .card .card-body {
            text-align: center;
            font-size: 18px;
        }
        #genderChart {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Gender Distribution of Heads of Household</h1>

    <!-- Statistics Cards -->
    <div class="row text-center">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">Total Households</div>
                <div class="card-body">
                    <h2><?php echo $totalHouseholds; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-header bg-info text-white">Male Heads</div>
                <div class="card-body">
                    <h2><?php echo $maleCount; ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">Female Heads</div>
                <div class="card-body">
                    <h2><?php echo $femaleCount; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Gender Distribution Chart -->
    <div class="text-center mt-5">
        <canvas id="genderChart" width="400" height="200"></canvas>
    </div>
    
    <script>
        var ctx = document.getElementById('genderChart').getContext('2d');
        var genderChart = new Chart(ctx, {
            type: 'doughnut', // Using 'doughnut' for a modern look
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    label: 'Gender Distribution',
                    data: [<?php echo $maleCount; ?>, <?php echo $femaleCount; ?>],
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    borderColor: ['#FFFFFF'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Heads of Household by Gender'
                },
                legend: {
                    position: 'bottom'
                }
            }
        });
    </script>
</div>

<!-- Bootstrap JS for enhanced responsiveness -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
