<!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="libs/css/main.css"></link>
      <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 50px;
        }

        .card2 {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin:0% 10% 10% 10%;
        }

        .preview {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-pic .pro_img {
            width: 100%;
            border-radius: 20px;

        }

        .details {
            padding: 30px;
            margin:10px;
        }

        .product-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .product-description {
            font-size: 14px;
            color: #888;
            margin-bottom: 30px;
        }

        .price {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .action {
            display: flex;
            align-items: center;
        }

        .add_to_cart,
        .add_to_watchlist {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .add_to_watchlist{
            margin:10px;
        }
        .add-_to_cart:hover,
        .add_to_watchlist:hover {
            background-color: #0056b3;
        }

        .out-of-stock {
            color: red;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
    </head>
    <body>
        <?php
          $page_title = 'Product Details';
          require_once('includes/load.php');
          include_once('layouts/header.php');
        ?>

        <?php
if (isset($_SESSION['user_id'])) {
    // User is logged in, you can proceed with adding the product to the cart.

    // Check if the Add to Cart button is clicked
    if (isset($_POST['add_to_cart'])) {
        // Get the product details from the form
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $user_id = $_SESSION['user_id'];
        $product_id = $_POST['product_id']; // Define $product_id

        // Establish a database connection
        $conn = mysqli_connect("localhost", "root", "", "inventory_system");

        // Check if the product exists in the products table
        $checkProductQuery = "SELECT id FROM products WHERE id = '$product_id'";
        $productExists = mysqli_query($conn, $checkProductQuery);

        if (mysqli_num_rows($productExists) > 0) {
            // The product exists in the products table, proceed with adding to the cart
        
            // Prepare and execute an INSERT query to add the product to the cart
            $insertQuery = "INSERT INTO orders (user_id, product_id, quantity)
                            VALUES ('$user_id', '$product_id', 1)"; // Assuming quantity is set to 1 for simplicity
        
            if (mysqli_query($conn, $insertQuery)) {
                // Product added to cart successfully
                // Display a success alert
                ?>
                <div class="alert alert-success" role="alert">
                    Product added to your cart successfully.
                </div>
                <?php
            } else {
                // Handle the case when the insertion fails
                // Display an error alert
                ?>
                <div class="alert alert-danger" role="alert">
                    This product is already added.
                </div>
                <?php
            }
        } else {
            // Handle the case when the product does not exist in the products table
            // Display an error alert
            ?>
            <div class="alert alert-danger" role="alert">
                Error: Product does not exist.
            </div>
            <?php
        }
        

        // Close the database connection
        mysqli_close($conn);
    }


    if (isset($_POST['add_to_watchlist'])) {
        // Get the product details from the form
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $user_id = $_SESSION['user_id'];
        $product_id = $_POST['product_id']; // Define $product_id

        // Establish a database connection
        $conn = mysqli_connect("localhost", "root", "", "inventory_system");

        // Check if the product exists in the products table
        $checkProductQuery = "SELECT id FROM products WHERE id = '$product_id'";
        $productExists = mysqli_query($conn, $checkProductQuery);

        if (mysqli_num_rows($productExists) > 0) {
            // The product exists in the products table, proceed with adding to the cart
        
            // Prepare and execute an INSERT query to add the product to the cart
            $insertQuery = "INSERT INTO watchlist (user_id, product_id, quantity)
                            VALUES ('$user_id', '$product_id', 1)"; // Assuming quantity is set to 1 for simplicity
        
            if (mysqli_query($conn, $insertQuery)) {
                // Product added to cart successfully
                // Display a success alert
                ?>
                <div class="alert alert-success" role="alert">
                    Product added to your Watchlist successfully.
                </div>
                <?php
            } else {
                // Handle the case when the insertion fails
                // Display an error alert
                ?>
                <div class="alert alert-danger" role="alert">
                    This product is already added.
                </div>
                <?php
            }
        } else {
            // Handle the case when the product does not exist in the products table
            // Display an error alert
            ?>
            <div class="alert alert-danger" role="alert">
                Error: Product does not exist.
            </div>
            <?php
        }
        

        // Close the database connection
        mysqli_close($conn);
    }
}
?>

        <?php
        // Check if the 'id' query parameter is set
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];
            
            // Establish a database connection
            $conn = mysqli_connect("localhost", "root", "", "inventory_system");
            
            // Prepare and execute a query to fetch the product details by ID
            $sql = "SELECT p.name, p.sale_price, p.Description, i.file_name ,p.quantity
                    FROM products AS p
                    LEFT JOIN media AS i ON p.media_id = i.id
                    WHERE p.id = $product_id";

            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                // Fetch the product details
                $record = mysqli_fetch_assoc($result);
                // Check if the user is logged in (you should have user authentication in place)
                if (isset($_SESSION['user_id'])) {

                } else {
                    ?>
                    <div class="alert alert-success" role="alert">
                    You have to login to add to your cart
                    <a href="sign_in.php" >Click Here</a>
                </div>
                <?php
                }
            } else {
                // Handle the case when the product is not found
                echo "Product not found.";
            }
            
            // Close the database connection
            mysqli_close($conn);
        } else {
            // Handle the case when 'id' is not set in the URL
            echo "Product ID is missing.";
        }
        ?>

	
	<div class="container">
		<div class="card2">
			<div class="container-fliud">
				<div class="wrapper row">
					<div class="preview col-md-4">
						
						<div class="preview-pic tab-content">
						  <div class="tab-pane active" id="pic-1"><img class="pro_img" src="uploads/products/<?php echo $record['file_name'];?>" /></div>
						</div>
						
						
					</div>
					<div class="details col-md-6">
                        <h3 class="product-title"><?php echo $record['name']; ?></h3>
                        <p class="product-description">Description: <?php echo $record['Description']; ?></p>
                        
                        <h4 class="price">Quantity: <?php echo $record['quantity']; ?></h4>
                        <h4 class="price"><?php echo $record['sale_price']; echo " $"; ?></h4>
                        
                        <div class="action">
                            <?php if ($record['quantity'] > 0) { ?>
                                <form action="" method="post">
                                    <input type="hidden" name="product_name" value="<?php echo $record['name']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $record['sale_price']; ?>">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <button class="add-to-cart btn btn-default" type="submit" name="add_to_cart">Add to cart</button>
                                </form>
                            <?php } else { ?>
                                <p class="out-of-stock" style="color:red;">This product is currently out of stock.</p>
                            <?php } ?>
                            <form action="" method="post">
                                    <input type="hidden" name="product_name" value="<?php echo $record['name']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $record['sale_price']; ?>">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <button class="fa fa-heart btn btn-default add_to_watchlist" type="submit" name="add_to_watchlist"style="background-color:#ff9f1a; color:white; padding:3%; margin-top:3px;"></button>
                                </form>
                        </div>
                    </div>
					</div>
				</div>
			</div>
		</div>
	</div>
    <?php  ?>

  </body>
    </html>


    