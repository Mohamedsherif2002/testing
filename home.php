<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="libs/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
</head>
<body class="sidepanel-closed">
    <?php
    $page_title = 'Home Page';
    require_once('includes/load.php');
    // if (!$session->isUserLoggedIn(true) ) {
    //     redirect('index.php', true);
    // }
    
    include_once('layouts/header.php');
    ?>

    <?php
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "inventory_system");

    // Fetch categories from the database
    $categoryQuery = "SELECT id, name FROM categories";
    $categoryResult = mysqli_query($conn, $categoryQuery);

    // Check for database errors
    if (!$categoryResult) {
        die("Database error: " . mysqli_error($conn));
    }

    // Generate dropdown options
    $categoryOptions = '';
    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
        $categoryId = $categoryRow['id'];
        $categoryName = $categoryRow['name'];

        // Add an option element for each category
        $categoryOptions .= "<option value='$categoryId'>$categoryName</option>";
    }

    // Handle product search
    $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    $category = isset($_GET['categorie']) ? $_GET['categorie'] : 'all'; // Get selected category, default to 'all'

    if ($category === 'all' && empty($search)) {
        // If 'all' category is selected without a search term, retrieve all products
        $sql = "SELECT p.id, p.name, p.quantity, p.sale_price, p.Description, i.file_name 
                FROM products AS p
                LEFT JOIN media AS i ON p.media_id = i.id";
    } elseif ($category !== 'all' && empty($search)) {
        // If a specific category is selected without a search term, retrieve products in that category
        $sql = "SELECT p.id, p.name, p.quantity, p.sale_price, p.Description, i.file_name 
                FROM products AS p
                LEFT JOIN media AS i ON p.media_id = i.id
                WHERE p.categorie_id = '$category'";
    } elseif (!empty($search)) {
        // If a search term is provided, handle the search in the selected category or all categories
        if ($category === 'all') {
            // Search in all categories
            $sql = "SELECT p.id, p.name, p.quantity, p.sale_price, p.Description, i.file_name 
                    FROM products AS p
                    LEFT JOIN media AS i ON p.media_id = i.id
                    WHERE p.name LIKE '%$search%'";
        } else {
            // Search in a specific category
            $sql = "SELECT p.id, p.name, p.quantity, p.sale_price, p.Description, i.file_name 
                    FROM products AS p
                    LEFT JOIN media AS i ON p.media_id = i.id
                    WHERE p.name LIKE '%$search%' AND p.categorie_id = '$category'";
        }
    }

    $resultset = mysqli_query($conn, $sql) or die("database error:" . mysqli_error($conn));
    ?>

    
    <!-- Add the search form with a category dropdown -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="search-form" >
    
        <div class="input-group search_ pull-right" >

            <div class="form-outline" >
                <input type="search" name="search" id="form1" class="form-control" placeholder="Search by name"style="  z-index: -1;" >
                <label class="form-label" for="form1"></label>
            </div>

            <select name="categorie" class="form-select" >
                <option  value="all">All Categories</option>
                <?php echo $categoryOptions; // Output generated category options ?>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </div>
    
    </form>
    <br>
    <br>

    <?php
    if (mysqli_num_rows($resultset) > 0) {
        // Display products if found
        while ($record = mysqli_fetch_assoc($resultset)) {
            // Truncate the description to 100 characters
            $truncatedDescription = strlen($record['Description']) > 100 ? substr($record['Description'], 0, 100) . ' ...' : $record['Description'];
        ?>
            <div class="card_containerr clickable-div" data-id="<?php echo $record['id']; ?>">
                <div class="card">
                    <img class="imgg" src="uploads/products/<?php echo $record['file_name']; ?>">
                    <div id="container">
                        <h4><b> <?php echo $record['name']; ?></b></h4>
                        <p> Quantity : <?php echo $record['quantity']; ?></p>
                        <p> Description : <?php echo $truncatedDescription; ?></p> <!-- Display truncated description -->
                        <div class="in">
                            <p class="label price_card label-warning pull-right"> <?php echo $record['sale_price']; echo " L.E"; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    } else {
        // Display a message if no products are found
        echo "<p>No products found.</p>";
    }
    ?>

<script>
  /* Toggle the sidebar open or closed and update body class */
  function toggleNav() {
    var body = document.body;
    var sidepanel = document.getElementById("mySidepanel");
    
    if (body.classList.contains("sidepanel-closed")) {
      // If the sidebar is closed, open it
      body.classList.remove("sidepanel-closed");
      body.classList.add("sidepanel-open");
      sidepanel.style.width = "250px";
    } else {
      // If the sidebar is open, close it
      body.classList.remove("sidepanel-open");
      body.classList.add("sidepanel-closed");
      sidepanel.style.width = "0";
    }
  }
</script>
<script>
            document.addEventListener("DOMContentLoaded", function() {
              var clickableDivs = document.querySelectorAll(".clickable-div");
              
              clickableDivs.forEach(function(div) {
                div.addEventListener("click", function() {
                  // Get the product ID from the data-id attribute
                  var productId = this.getAttribute("data-id");
                  
                  // Construct the URL for product_details.php with the product ID
                  var url = "product_details.php?id=" + productId;
                  
                  // Navigate to the URL
                  window.location.href = url;
                });
              });
            });
          </script>
    

    <?php include_once('layouts/footer.php'); ?>

</body>
</html>
