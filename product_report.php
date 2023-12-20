<?php
$page_title = 'Product Report';
require_once('includes/load.php');
// Checking what level user has permission to view this page
page_require_level(2);
?>

<?php include_once('layouts/header.php'); ?>

<div class="row" >
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row"style="margin-left:20%;">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-heading">
      </div>
      <div class="panel-body">
      <form class="clearfix" method="post" action="product_report_process.php">
    <div class="form-group">
        <label class="form-label">Product</label>
        <select name="product_id" class="form-control" id="productDropdown">
            <option value="">Select a product</option>
            <?php
            // Connect to your MySQL database
            $mysqli = new mysqli("localhost", "root", "", "inventory_system");

            // Check for connection errors
            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            // Query to select all products from your products table
            $query = "SELECT id, name FROM products";
            $result = $mysqli->query($query);

            // Loop through the results and generate options
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['id'] . " : " . $row['name'] . "</option>";
            }

            // Close the database connection
            $mysqli->close();
            ?>
        </select>
    </div>

    <div class="form-group">
        <button type="submit" name="submit_product" class="btn btn-primary">Generate Report</button>
    </div>
</form>
<script>
    document.querySelector('form').addEventListener('submit', function (event) {
        var productDropdown = document.getElementById('productDropdown');
        var selectedOption = productDropdown.options[productDropdown.selectedIndex];
        var productValue = selectedOption.value;
        var productName = selectedOption.text;

        // Set the chosen product ID and name as hidden fields
        document.getElementById('product_id').value = productValue;
        document.getElementById('product_name').value = productName;
    });
</script>

      </div>
    </div>
  </div>
</div>




<?php include_once('layouts/footer.php'); ?>
