<?php
// delete_item.php

session_start();

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Create a connection to the database
    $conn = mysqli_connect("localhost", "root", "", "inventory_system");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Delete the item from the carts table
    $user = $_SESSION['user_id'];
    $sql = "DELETE FROM orders WHERE product_id = $product_id AND user_id = $user";
    $result = mysqli_query($conn, $sql);

    // Close the database connection
    mysqli_close($conn);

    echo ($result) ? "Success" : "Error";
} else {
    echo "Invalid request";
}
?>
