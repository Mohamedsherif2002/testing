<!DOCTYPE html>

<?php 
session_start();
error_reporting(E_ALL ^ E_NOTICE);
$conn = mysqli_connect("localhost", "root", "", "inventory_system");
    
if(isset($_POST['final_total']))
{ 
    header("location:payment.php"); 
}

elseif(isset($_POST['delete']))
{
    $z=$_POST['product_id'];
    $user=$_SESSION['user_id'];
    $sql = "DELETE FROM orders WHERE product_id=$z AND user_id=$user";
    $result = mysqli_query($conn, $sql);
    if($result)
    {
    header("location:cart.php");
    }
}
elseif(isset($_POST['product_id']))
{
   
        $z=$_POST['product_id'];
        $user=$_SESSION['user_id'];
        $x= $_POST['quantity'];
        $sql = "UPDATE orders SET quantity=$x WHERE product_id=$z AND user_id=$user";
        $result = mysqli_query($conn, $sql);
        if($result)
        {
        header("location:cart.php");
        }


}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="libs/css/main.css">
    <?php
    $page_title = 'Product Details';
    require_once('includes/load.php');
    include_once('layouts/header.php');
    ?>
    <?php

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to the login or registration page
        echo"You need to login first";
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
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th></th>
                </tr>
                </thead>
                <?php
                $user_id = $_SESSION['user_id'];
                $individualProductTotal = 0; // Initialize individual product total
                $finalTotal = 0; // Initialize final total

                if (!isset($_SESSION['user_id'])) {
                    // Redirect to the login or registration page
                    header("Location: login.php");
                    exit(); // Ensure script execution stops here
                }
                $_SESSION['finalTotal'] = $finalTotal; // Set the session variable

                // Establish a database connection
                $conn = mysqli_connect("localhost", "root", "", "inventory_system");

                // Create a SQL query to join the orders and products tables
                $sql = "SELECT o.product_id, p.name, p.sale_price, o.quantity,p.quantity as pquantity
                        FROM orders o
                        INNER JOIN products p ON o.product_id = p.id
                        WHERE o.user_id = '$user_id'";

                // Execute the query
                $result = mysqli_query($conn, $sql);
                $order_data = array(); // Create an array to store order data

                if ($result) {
                    $c=1;
                    while ($row = mysqli_fetch_assoc($result)) {
                       
                        
                        $product_id = $row['product_id'];
                        $product_name = $row['name'];
                        $product_price = $row['sale_price'];
                        $quantity = $row['quantity'];
                        $pquantity = $row['pquantity'];

                        // Calculate the total for this product
                        $total = $product_price * $quantity;

                        // Update the individual product total
                        $individualProductTotal += $total;

                        // ... 
                        ?>
                        <tr id=<?php echo "r".$c; ?>>
                            <td><?php echo $product_name; ?></td>
                            <td id=<?php echo "mainPrice".$c; ?>><?php echo $product_price; ?></td>
                            <td>
                                <div class="form-outline">
                                <form id=<?php echo "form".$c ?> method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
                                    <input type="number"  min="1" max=<?php echo $pquantity?> id=<?php echo "quantity".$c; ?> onchange="
                                    if(document.getElementById('<?php echo 'quantity'.$c; ?>').value<=0)
                                    {
                                        document.getElementById('<?php echo 'quantity'.$c; ?>').value=1; document.getElementById('<?php echo 'totalPrice'.$c; ?>').innerHTML=document.getElementById('<?php echo 'mainPrice'.$c; ?>').innerHTML*document.getElementById('<?php echo 'quantity'.$c; ?>').value; 
                                    }
                                    else if(document.getElementById('<?php echo 'quantity'.$c; ?>').value><?php echo $pquantity?>)

                                    {
                                        document.getElementById('<?php echo 'quantity'.$c; ?>').value=<?php echo $pquantity?>; document.getElementById('<?php echo 'totalPrice'.$c; ?>').innerHTML=document.getElementById('<?php echo 'mainPrice'.$c; ?>').innerHTML*document.getElementById('<?php echo 'quantity'.$c; ?>').value;  
                                    }
                                    else
                                    {
                                             document.getElementById('<?php echo 'totalPrice'.$c; ?>').innerHTML=document.getElementById('<?php echo 'mainPrice'.$c; ?>').innerHTML*document.getElementById('<?php echo 'quantity'.$c; ?>').value;
                                    }
                                    document.getElementById('<?php echo 'form'.$c ?>').requestSubmit();
                                    "

                                            required 
                                            class="form-control quantity-input"
                                           style="width:100px;" 
                                           value="<?php echo $quantity; ?>" name="quantity"
                                           />
                                           <input type="hidden" name="product_id" value=<?php echo $product_id ?> />
                                </form>
                                </div>
                            </td>
                            <td>
                                <p class="fw-bold total-cell" style="text-align: left;" id=<?php echo "totalPrice".$c; ?>><?php echo $total; ?></p>
                            </td>

                            <td>
                            <!-- Create a form for each delete button -->
                            <form id=<?php echo "form".$c ?> method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
                                    <input type="hidden" name="product_id"  value=<?php echo $product_id ?> />
                                    <input type="submit" class="btn btn-danger" value="delete"name="delete" >
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
                $finalTotal = $individualProductTotal ; // Assuming $20 for shipping

                // Set the final total in the session
                // $_SESSION['finalTotal'] = $finalTotal;

                 if(isset( $_COOKIE['finalTotal']))
                {
                   $_SESSION['finalTotal'] =$_COOKIE['finalTotal'];
                }

                
                
                
                // Close the database connection
                mysqli_close($conn);
                ?>
            </table>
            <br>
            <!-- Display Individual Product Total -->
            <form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
                <input type="hidden" name="final_total" value="<?php echo $finalTotal; ?>">
                <!-- Other form elements -->
                <button class="btn btn-outline-primary" name="final_total" id="checkoutButton" type="submit">Checkout</button>
            </form>

        </div>
    </div>
</div>


<script>
  

var body=document.body;

body.onload=function()
{
    var temp=0; 
 for(var i=1;i< <?php echo $c ?>;i++)
    {
        var x='totalPrice'+i;console.log(temp,x,document.getElementById(x).innerHTML);temp+=parseInt(document.getElementById(x).innerHTML);
    }
 document.getElementById('checkoutButton').innerHTML="checkout("+temp+")"; 
    document.cookie= "finalTotal = "+temp; 
    if(temp==0)
    {
        document.getElementById("checkoutButton").disabled=true
        document.getElementById("checkoutButton").innerHTML="checkout";
    }

}

body.onchange=function()
{
    var temp=0; 
 for(var i=1;i< <?php echo $c ?>;i++)
    {
        var x='totalPrice'+i; console.log(temp,x,document.getElementById(x).innerHTML);temp+=parseInt(document.getElementById(x).innerHTML);
    }
 document.getElementById('checkoutButton').innerHTML="checkout("+temp+")"; 
 
 document.cookie= "finalTotal = "+temp; 
    if(temp==0)
    {
        document.getElementById("checkoutButton").disabled=true;
        document.getElementById("checkoutButton").innerHTML="checkout";
    }


}



</script>

<!-- <script>
    var quantity=document.getElementById("quantity");
    var mainPrice=document.getElementById("mainPrice");
    var totalPrice=document.getElementById("totalPrice");

    quantity.onchange=function()
    {
    console.log(quantity);
    console.log(mainPrice);
    console.log(totalPrice);
    totalPrice.innerHTML=mainPrice.innerHTML*quantity.value;
    }
</script> -->




<?php include_once('layouts/footer.php'); ?>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<?php include_once('layouts/footer.php'); ?>