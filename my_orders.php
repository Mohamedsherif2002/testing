<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="libs/css/main.css">
    <?php
    $page_title = 'Your Orders';
    require_once('includes/load.php');
    include_once('layouts/header.php');
    ?>
    <?php

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to the login or registration page if the user is not logged in
    echo "You need to login first.";
    exit(); // Ensure script execution stops here
}

   
    
    
    ?>
    <!-- //Bootstrap CDN -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous"/>
</head>
<body id="body">
<div class="main">
    <div class="container">
        <div class="main-sub row align-items-center">
        </div>
        <div class="table-container ">
            <div class="mb-2">
                <h2 class="">Your Orders</h2>
            </div>
            <table id="mytable" class="table align-middle mb-0 bg-white">
                <thead class="bg-light">
                <tr class="header-row">
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Order_ID</th>

                    <th style="text-align:right;">Date</th>

                </tr>
                </thead>
                <?php
                $user_id = $_SESSION['user_id'];

               

                // Establish a database connection
                $conn = mysqli_connect("localhost", "root", "", "inventory_system");

                // Create a SQL query to join the watchlist and products tables
                $sql = "SELECT s.order_id, s.qty, s.date,s.price, p.name 
                FROM sales s
                INNER JOIN products p ON s.product_id = p.id
                WHERE s.user_id = '$user_id'
                ORDER BY s.date DESC";

                // Execute the query
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $order_name = $row['name'];
                        $order_qty = $row['qty'];
                        $order_date = $row['date'];
                        $order_id = $row['order_id'];
                        $order_price = $row['price'];
                        ?>

                        <tr >
                            <td><?php echo $order_name; ?></a></td>
                            <td ><?php echo $order_qty; ?></td>
                            <td ><?php echo $order_price ." L.E"; ?></td>
                            <td><?php echo $order_id; ?></td>
                            <td><?php echo $order_date; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    // Handle any errors that may occur during the query
                    echo "Error: " . mysqli_error($conn);
                }

                // Close the database connection
                mysqli_close($conn);
                ?>
            </table>
            <?php include_once('layouts/footer.php'); ?>
        </div>
    </div>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</html>
