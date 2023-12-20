<?php
// Check if the form is submitted
if (isset($_POST['delete_order'])) {
    // Get the product_id and user_id from the form
    $product_id = $_POST['product_id'];
    $user_id = $_POST['user_id'];

    // Establish a database connection
    $conn = mysqli_connect("localhost", "root", "", "inventory_system");

    // Create a SQL query to delete the order
    $sql = "DELETE FROM orders WHERE product_id = '$product_id' AND user_id = '$user_id'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Deletion successful
        header("Location: Cart.php"); // Redirect to your orders page or any other appropriate page
        exit();
    } else {
        // Handle the error if the deletion fails
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}
?>