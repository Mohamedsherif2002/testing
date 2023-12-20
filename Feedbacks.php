<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="libs/css/main.css">
    <?php
    $page_title = 'Feedbacks';
    require_once('includes/load.php');
    include_once('layouts/header.php');
    ?>
    <?php
    page_require_level(3);



    if (isset($_POST['Delete_feedback'])) {
        $feedback_id = $_POST['feedback_id']; // Change 'name' to 'feedback_id'
    
        // Establish a database connection
        $conn = mysqli_connect("localhost", "root", "", "inventory_system");
    
        // Create a SQL query to delete the feedback with the specified ID
        $deleteQuery = "DELETE FROM conatacts WHERE id = $feedback_id";
    
        // Execute the query
        $deleteResult = mysqli_query($conn, $deleteQuery);
    
        if ($deleteResult) {
            // The feedback was successfully deleted
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
                <h2 class="">Feedbacks</h2>
            </div>
            <table id="mytable" class="table align-middle mb-0 bg-white">
                <thead class="bg-light">
                <tr class="header-row">
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th></th>
                </tr>
                </thead>
                <?php
                $user_id = $_SESSION['user_id'];

                if (!isset($_SESSION['user_id'])) {
                    // Redirect to the login or registration page
                    header("Location: login.php");
                    exit(); // Ensure script execution stops here
                }

                // Establish a database connection
                $conn = mysqli_connect("localhost", "root", "", "inventory_system");

                // Create a SQL query to join the watchlist and products tables
                $sql = "SELECT id,name,email,subject,message 
                        FROM conatacts ";

                // Execute the query
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    $f_id = $row['id']; // Assuming you have a unique ID for each feedback
                    $f_name = $row['name'];
                    $f_email = $row['email'];
                    $f_subject = $row['subject'];
                    $f_message = $row['message'];
                    ?>
                
                    <tr id="<?php echo "r".$c; ?>">
                        <td><?php echo $f_name; ?></td>
                        <td><?php echo $f_email; ?></td>
                        <td><?php echo $f_subject; ?></td>
                        <td><?php echo $f_message; ?></td>
                        <td>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="feedback_id" value="<?php echo $f_id; ?>" />
                                <button type="submit" class="btn btn-danger" name="Delete_feedback">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php
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

