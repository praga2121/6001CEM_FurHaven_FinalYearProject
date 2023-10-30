<?php
// Database connection configuration
$host = 'localhost';
$username = 'root';
$password = "";
$database = 'pet_shop_db';

$connection = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<?php
// Perform a SELECT query to retrieve client data
$query = "SELECT * FROM clients";
$result = mysqli_query($connection, $query);

// Check if the query was successful
if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
?>

<style>
    table.table th,
    table.table td {
        color: white;
    }
</style>

<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Clients</h3> <br><br>
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Default Delivery Address</th>
                <th>Date Created</th> 
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['firstname']}</td>";
                echo "<td>{$row['lastname']}</td>";
                echo "<td>{$row['gender']}</td>";
                echo "<td>{$row['contact']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['default_delivery_address']}</td>"; 
                echo "<td>{$row['date_created']}</td>"; 
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// Close the database connection
mysqli_close($connection);
?>