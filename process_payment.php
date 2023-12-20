<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout_product'])) {
    // Retrieve form data
    $finalTotal = isset($_POST['final_total']) ? floatval($_POST['final_total']) : 0.0;
    session_start();
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        // Handle the case where the user ID is not set in the session
        echo "User ID not set in the session.";
        exit(); // Exit the script
    }
    $cardholderName = $_POST["cardholder_name"];
    $cardNumber = $_POST["card_number"];
    $cardExpiration = $_POST["card_expiration"];
    $cardCVV = $_POST["card_cvv"];
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $number = $_POST["number"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $address2 = $_POST["address2"];

    // Get the current timestamp (including microseconds for added uniqueness)
    $timestamp = microtime(true);
    // Generate a random number (you can customize the length as needed)
    $randomNumber = mt_rand(1000, 99999);
    // Combine the timestamp and random number to create the order ID
    $order_id = 'ORD' . $user_id . $randomNumber;
    
    // Retrieve user ID from the session
    

    // Establish a database connection
    $conn = mysqli_connect("localhost", "root", "", "inventory_system");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Execute a SELECT query to retrieve rows from the "orders" table for the user
    $select_query = "SELECT * FROM orders WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $select_query);

    if ($result) {
        // Initialize a flag to track whether all updates, deletes, and inserts are successful
        $all_operations_successful = true;

        // Process the orders here
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['product_id'];
            $quantity = $row['quantity'];
            date_default_timezone_set('Africa/Cairo');
            $date = date("Y-m-d H:i:s");

            // Update the quantity in the "products" table
            $update_query = "UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id";
            if (!mysqli_query($conn, $update_query)) {
                // If an update fails, set the flag to false and break the loop
                $all_operations_successful = false;
                break; // This break statement exits the loop after processing the first product
            }

            // Insert the sale into the "sales" table
            $insert_query = "INSERT INTO sales (user_id, order_id, product_id, qty, date , price) VALUES ('$user_id', '$order_id', '$product_id', '$quantity', '$date','$finalTotal')";
            if (!mysqli_query($conn, $insert_query)) {
                // If an insert fails, set the flag to false and break the loop
                $all_operations_successful = false;
                break;
            }
            // Insert the checkout information into the "checkouts" table
            $checkout_date = date("Y-m-d H:i:s");                                                       
            $insert_checkout_query = "INSERT INTO checkouts (user_id, order_id, total_price, date,cardholderName ,cardNumber ,cardExpiration ,cardCVV ,firstName ,lastName ,number ,email ,address ,address2) VALUES 
                                            ('$user_id', '$order_id', '$finalTotal', '$checkout_date' , '$cardholderName', '$cardNumber', '$cardExpiration', '$cardCVV', '$firstName', '$lastName' ,'$number' ,'$email' ,'$address' ,'$address2')";
            if (!mysqli_query($conn, $insert_checkout_query)) {
                
                // If an insert fails, set the flag to false
                $all_operations_successful = false;
                echo "Insert checkout query failed: " . mysqli_error($conn);

            }
            else{echo "Payment processed successfully";
            }
            // Delete the order from the "orders" table
            $delete_query = "DELETE FROM orders WHERE user_id = '$user_id' AND product_id = '$product_id'";
            if (!mysqli_query($conn, $delete_query)) {
                // If a delete fails, set the flag to false and break the loop
                $all_operations_successful = false;
                break;
            }
        }

        echo "The Order ID is : " . $order_id . "<br>"."Payment successful";



        // If all updates, deletes, and inserts were successful, commit the changes
        if ($all_operations_successful) {
            // Concatenate the message with Order ID and user ID
            $message = "Order ID: $order_id, User ID: $user_id, Payment successful";
            
            // Redirect to home.php with the message as a URL parameter
            header("Location: payment_success.php?message=" . urlencode($message));
            exit();
        } else {
            // If any operation failed, rollback the changes
            mysqli_rollback($conn);
            echo "Payment successful, but there was an issue updating product quantities, deleting orders, or inserting sales. Please contact support.";
        }
    } else {
        // Handle the case where the SELECT query fails
          "Failed to retrieve orders from the Cart.";
    }

    // Close the database connection
    mysqli_close($conn);
}

?>
<script>
// Check if a message is present in the URL
var urlParams = new URLSearchParams(window.location.search);
var message = urlParams.get('message');

// Display an alert if a message is present
if (message) {
    alert(message);
    // Redirect to home.php after the user closes the alert
    window.location.href = 'home.php';
}
</script>
