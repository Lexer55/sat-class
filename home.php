<?php
include 'db.php';

$query = "SELECT date, diesel_price, unleaded_price FROM weekly_gas_prices";
$result = mysqli_query($conn, $query);

$weeks = [];
$dieselPrices = [];
$unleadedPrices = [];

while ($row = mysqli_fetch_assoc($result)) {
    $weeks[] = $row['date'];
    $dieselPrices[] = $row['diesel_price'];
    $unleadedPrices[] = $row['unleaded_price'];
}

mysqli_free_result($result);

$query1 = "SELECT month, diesel_change, unleaded_change FROM monthly_price_changes";
$result1 = mysqli_query($conn, $query1);

$months = [];
$dieselChange = [];
$unleadedChange = [];

while ($row = mysqli_fetch_assoc($result1)) {
    $months[] = $row['month'];
    $dieselChange[] = $row['diesel_change'];
    $unleadedChange[] = $row['unleaded_change'];
}

mysqli_free_result($result1);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Line Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #weekly, #monthly {
            width: 3px;
            height: 1px;
        }
        a {
            float: right;
        }
    </style>
</head>
<body>
    <a href="login.php">Login</a>
    <br>
    <a href="map.php">Maps</a>
    <h1>HOME PAGE</h1>
    <h2><center>Weekly Gas Prices</center></h2>
    <canvas id="weekly"></canvas>
    <script>
        const inside = document.getElementById('weekly').getContext('2d');
        const weekly = new Chart(inside, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($weeks); ?>,
                datasets: [
                    {
                        label: 'Diesel Price',
                        data: <?php echo json_encode($dieselPrices); ?>,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2
                    },
                    {
                        label: 'Unleaded Price',
                        data: <?php echo json_encode($unleadedPrices); ?>,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Price'
                        }
                    }
                }
            }
        });
    </script>

    <h2><center>Monthly Gas Prices</center></h2>
        <canvas id="monthly"></canvas>
        <script>
            const inside1 = document.getElementById('monthly').getContext('2d');
            const month = new Chart(inside1, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($months); ?>,
                    datasets: [
                        {
                            label: 'Diesel Price',
                            data: <?php echo json_encode($dieselChange); ?>,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 2
                        },
                        {
                            label: 'Unleaded Price',
                            data: <?php echo json_encode($unleadedChange); ?>,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Price'
                            }
                        }
                    }
                }
            });
        </script>
</body>
</html>
