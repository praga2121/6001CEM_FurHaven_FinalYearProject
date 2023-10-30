<?php
// Include your database connection and configuration here
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

// Retrieve and display reviews for the current product
$product_id = $_GET["product_id"];
$reviews_query = $conn->query("SELECT * FROM reviews WHERE product_id = '$product_id'");

if ($reviews_query->num_rows > 0) {
    while ($row = $reviews_query->fetch_assoc()) {
        $username = $row['username'];
        $comment = $row['comment'];
        $rating = $row['rating']; // Retrieve the rating value
        $stars = str_repeat('&#9733;', $rating); // Convert rating to star representation

        // Display the username, star rating, and comment for each review
        echo "<div class='review'>";
        echo "<p><strong>Username:</strong> $username</p>";
        echo "<p><strong>Star Rating:</strong> $stars</p>"; // Display star rating
        echo "<p><strong>Comment:</strong> $comment</p>";
        echo "</div>";
    }
} else {
    echo "No reviews available for this product.";
}

// Close the database connection here
mysqli_close($conn);
?>