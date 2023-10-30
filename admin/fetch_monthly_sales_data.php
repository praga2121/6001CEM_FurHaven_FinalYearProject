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

// Fetch monthly sales data from the sales table
$query = "SELECT DATE_FORMAT(date_created, '%Y-%m') AS label, SUM(total_amount) AS data FROM sales GROUP BY label ORDER BY label";
$result = $conn->query($query);

$salesData = [];
while ($row = $result->fetch_assoc()) {
    $salesData[] = [
        'label' => $row['label'],
        'data' => $row['data'],
    ];
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode(['sales' => $salesData]);
?>
