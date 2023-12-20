
<?php
require_once('includes/load.php');

// Check what level user has permission to view this page
page_require_level(2);
$product_result='';
?>

<?php

// Check if the form was submitted
if (isset($_POST['submit_product'])) {
    // Get the chosen product's ID from the form
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;

    // Check if a product was selected
    if ($product_id > 0) {
        // Connect to your MySQL database
        $mysqli = new mysqli("localhost", "root", "", "inventory_system");

        // Check for connection errors
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Query to retrieve product details (name, sale_price, buy_price)
        $product_query = "SELECT p.name AS product_name, p.sale_price, p.buy_price,
        s.order_id, s.date, s.qty
        FROM products p
        LEFT JOIN sales s ON p.id = s.product_id
        WHERE p.id = $product_id
        ORDER BY s.date DESC"; 
               
        $product_result = $mysqli->query($product_query);


        // Close the database connection
        $mysqli->close();
    } else {
        // Handle the case where no product was selected
        $session->msg("d", "Select Product");
        redirect('product_report.php', false);    }
}

// Rest of your code to display the results
?>

<!doctype html>
<html lang="en-US">
<head>
<style>
   @media print {
     html,body{
        font-size: 9.5pt;
        margin: 0;
        padding: 0;
     }.page-break {
       page-break-before:always;
       width: auto;
       margin: auto;
      }
    }
    .page-break{
      width: 980px;
      margin: 0 auto;
    }
     .sale-head{
       margin: 40px 0;
       text-align: center;
     }.sale-head h1,.sale-head strong{
       padding: 10px 20px;
       display: block;
     }.sale-head h1{
       margin: 0;
       border-bottom: 1px solid #212121;
     }
     .table{
        width:100%;
     }.table>thead:first-child>tr:first-child>th{
       border-top: 1px solid #000;
      }
      table thead tr th {
       text-align: center;
       border: 1px solid #ededed;
     }table tbody tr td{
       vertical-align: middle;
     }.sale-head,table.table thead tr th,table tbody tr td,table tfoot tr td{
       border: 1px solid #212121;
       white-space: nowrap;
     }.sale-head h1,table thead tr th,table tfoot tr td{
       background-color: #f8f8f8;
     }tfoot{
       color:#000;
       text-transform: uppercase;
       font-weight: 500;
     }
   </style>
</head>
<body>
<div class="page-break">
       <div class="sale-head">
           <h1>Inventory Management System - Sales Report</h1>
           <strong> ID : <?php echo $product_id ;?>
           <strong> Product : 
    <?php
    if (!empty($product_result) && $product_result->num_rows > 0) {
        $product_row = $product_result->fetch_assoc();
        echo $product_row['product_name'];
    } else {
        echo "Product Not Found";
    }
    ?>
</strong>       </div>
      <table class="table table-border">
        <thead>
          <tr>
              <th>Date</th>
              <th>Product Name</th>
              <th>Buying Price</th>
              <th>Selling Price</th>
              <th>Total Qty</th>
              <th>TOTAL</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($product_result as $result): ?>
    <tr>
        <td class=""><?php echo remove_junk($result['date']); ?></td>
        <td class="desc">
            <h6><?php echo remove_junk(ucfirst($result['product_name'])); ?></h6>
        </td>
        <td class="text-right"><?php echo remove_junk($result['buy_price']); ?></td>
        <td class="text-right"><?php echo remove_junk($result['sale_price']); ?></td>
        <td class="text-right"><?php echo remove_junk($result['qty']); ?></td>
        <td class="text-right">
    <?php
    $totalSale = $result['sale_price'] * $result['qty'];
    echo number_format($totalSale, 2);
    ?>
</td>    </tr>
<?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr class="text-right">
    <td colspan="4"></td>
    <td colspan="1">Grand Total</td>
    <td>$
        <?php
        $grandTotal = 0;
        if (!empty($product_result)) {
            foreach ($product_result as $result) {
                $grandTotal += $result['sale_price'] * $result['qty'];
            }
        }
        echo number_format($grandTotal, 2);
        ?>
    </td>
    </tr>
    <tr class="text-right">
        <td colspan="4"></td>
        <td colspan="1">Profit</td>
        <td> $
            <?php
            $profit = $grandTotal - $result['buy_price'] * $result['qty'];
            echo number_format($profit, 2);
            ?>
        </td>
    </tr>
        </tfoot>
      </table>
    </div>
    <!-- Your HTML content to display the product details and sales data -->
    <?php if (isset($product_result) && $product_result->num_rows > 0) : ?>
        <!-- Display product details here -->
        <?php while ($product_row = $product_result->fetch_assoc()) : ?>
            <p>Product Name: <?php echo $product_row['name']; ?></p>
            <p>Sale Price: <?php echo $product_row['sale_price']; ?></p>
            <p>Buy Price: <?php echo $product_row['buy_price']; ?></p>
        <?php endwhile; ?>
    <?php endif; ?>


    <?php if (isset($error_message)) : ?>
        <!-- Display error message if no product was selected -->
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
</body>
</html>
