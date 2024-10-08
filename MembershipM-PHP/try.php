<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="path/to/your/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<?php
function getGenderDistribution() {
    global $conn;
    $genderDistribution = ['Male' => 0, 'Female' => 0, 'Other' => 0];

    $genderQuery = "SELECT gender, COUNT(*) AS count FROM members GROUP BY gender";
    $genderResult = $conn->query($genderQuery);

    while ($row = $genderResult->fetch_assoc()) {
        if (isset($genderDistribution[$row['gender']])) {
            $genderDistribution[$row['gender']] = $row['count'];
        }
    }

    return $genderDistribution;
}
?>
<body>
    <div class="dashboard">
        <h1>Dashboard</h1>
       
        <div class="gender-distribution">
            <h3>Gender Distribution</h3>
            <canvas id="genderChart" width="400" height="200"></canvas>
        </div>
    </div>

    <script>
        // Get gender distribution data
        const genderDistribution = <?php echo json_encode(getGenderDistribution()); ?>;

        // Chart.js implementation
        const ctx = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Male', 'Female', 'Other'], // Gender labels
                datasets: [{
                    label: 'Number of Members',
                    data: [genderDistribution['Male'], genderDistribution['Female'], genderDistribution['Other']], // Gender data
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)', // Male color
                        'rgba(255, 99, 132, 0.2)', // Female color
                        'rgba(255, 206, 86, 0.2)'  // Other color
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
