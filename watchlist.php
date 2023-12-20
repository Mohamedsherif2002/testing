<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="libs/css/main.css">
    <?php
    $page_title = 'watchlist';
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

    if (isset($_POST['delete_product'])) {
        $product_id = $_POST['product_id'];
    
        // Establish a database connection
        $conn = mysqli_connect("localhost", "root", "", "inventory_system");
    
        // Create a SQL query to delete the product from the watchlist table
        $deleteQuery = "DELETE FROM watchlist WHERE user_id = '$user_id' AND product_id = '$product_id'";
    
        // Execute the query
        $deleteResult = mysqli_query($conn, $deleteQuery);
    
        if ($deleteResult) {
            // The product was successfully deleted from the watchlist
            // You can display a success message here if needed
        } else {
            // Handle any errors that may occur during the query
            echo "Error: " . mysqli_error($conn);
        }
    
        // Close the database connection
        mysqli_close($conn);
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
                <h2 class="">Your watchlist</h2>
            </div>
            <table id="mytable" class="table align-middle mb-0 bg-white">
                <thead class="bg-light">
                <tr class="header-row">
                    <th>Product Name</th>
                    <th>Price</th>
                    <th style="text-align:right;">Action</th>
                </tr>
                </thead>
                <?php
                $user_id = $_SESSION['user_id'];

                

                // Establish a database connection
                $conn = mysqli_connect("localhost", "root", "", "inventory_system");

                // Create a SQL query to join the watchlist and products tables
                $sql = "SELECT o.product_id, p.name, p.sale_price
                        FROM watchlist o
                        INNER JOIN products p ON o.product_id = p.id
                        WHERE o.user_id = '$user_id'";

                // Execute the query
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $c=1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $product_name = $row['name'];
                        $product_price = $row['sale_price'];
                        $product_id = $row['product_id'];
                        ?>
                        <tr id=<?php echo "r".$c; ?>>
                            <td><a href="product_details.php?id=<?php echo $product_id; ?>"><?php echo $product_name; ?></a></td>
                            <td id=<?php echo "mainPrice".$c; ?>><?php echo $product_price ." L.E" ;?></td>
                            <td>
                                <!-- Create a form for each delete button -->
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                                    <button type="submit" class="btn btn-danger" name="delete_product">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                        $c+=1;
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
