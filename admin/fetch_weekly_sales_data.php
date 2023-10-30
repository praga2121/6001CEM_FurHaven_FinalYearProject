<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "pet_shop_db";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch weekly sales data from the sales table
// $query = "SELECT DATE_FORMAT(date_created, '%Y-%U') AS label, SUM(total_amount) AS data FROM sales GROUP BY label ORDER BY label"; //Weekly Base
// $query = "SELECT DATE_FORMAT(date_created, '%Y-%m-%d') AS label, SUM(total_amount) AS data FROM sales GROUP BY label ORDER BY label"; //Specified date based

$query = "SELECT 
    MIN(date_created) AS week_start, 
    DATE_ADD(MIN(date_created), INTERVAL 6 DAY) AS week_end, 
    SUM(total_amount) AS data 
    FROM sales 
    GROUP BY YEAR(date_created), WEEK(date_created) 
    ORDER BY week_start";

$result = $conn->query($query);

$salesData = [];
while ($row = $result->fetch_assoc()) {
    $week_start = date("Y-m-d", strtotime($row['week_start']));
    $week_end = date("Y-m-d", strtotime($row['week_end']));
    $salesData[] = [
        'label' => $week_start . '  to  ' . $week_end,
        'data' => $row['data'],
    ];
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode(['sales' => $salesData]);
?>
