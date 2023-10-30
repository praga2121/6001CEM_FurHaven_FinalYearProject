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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $username = $_POST["username"]; // Add username input
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];

    // Calculate the star rating based on the numerical rating
    $stars = str_repeat('*', $rating);

    // Insert the review into the database
    $insert_review = $conn->prepare("INSERT INTO reviews (product_id, username, rating, comment, stars) VALUES (?, ?, ?, ?, ?)");
    $insert_review->bind_param("sssss", $product_id, $username, $rating, $comment, $stars);

    if ($insert_review->execute()) {
        // Review inserted successfully
        // You can optionally redirect the user back to the product page
        header("Location: .?p=view_product&id=" . $product_id);
        exit();
    } else {
        // Error handling
        echo "Error: " . $conn->error;
    }
}

// Close the database connection here
mysqli_close($conn);
?>
