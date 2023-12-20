<?php
require_once('includes/load.php');
include_once('layouts/header.php');

?>
<?php

// Process the form submission only when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Create a connection to the database
    $conn = mysqli_connect("localhost", "root", "", "inventory_system");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO conatacts (name, email, subject, message) VALUES (?, ?, ?, ?)");

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters to the prepared statement
$stmt->bind_param("ssss", $name, $email, $subject, $message);

// Check if binding parameters was successful
if ($stmt === false) {
    die("Error binding parameters: " . $stmt->error);
}

// Execute the prepared statement
if ($stmt->execute()) {
    echo '<div class="notification" >';
    echo "<p>Thank You!</p>";
    echo '</div>';
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close the prepared statement
$stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Contact Us</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="ContactFrom_v1/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="ContactFrom_v1/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="ContactFrom_v1/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="ContactFrom_v1/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="ContactFrom_v1/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="ContactFrom_v1/css/util.css">
    <link rel="stylesheet" type="text/css" href="ContactFrom_v1/css/main.css">
    <!--===============================================================================================-->
</head>
<body>

<div class="contact1">
    <div class="container-contact1">
        <div class="contact1-pic js-tilt" data-tilt>
            <img src="uploads/products/img-01.png" alt="IMG">
        </div>

        <form class="contact1-form validate-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <span class="contact1-form-title">
                Get in touch
            </span>

            <div class="wrap-input1 validate-input" data-validate="Name is required">
                <input class="input1" type="text" name="name" placeholder="Name">
                <span class="shadow-input1"></span>
            </div>

            <div class="wrap-input1 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                <input class="input1" type="text" name="email" placeholder="Email">
                <span class="shadow-input1"></span>
            </div>

            <div class="wrap-input1 validate-input" data-validate="Subject is required">
                <input class="input1" type="text" name="subject" placeholder="Subject">
                <span class="shadow-input1"></span>
            </div>

            <div class="wrap-input1 validate-input" data-validate="Message is required">
                <textarea class="input1" name="message" placeholder="Message"></textarea>
                <span class="shadow-input1"></span>
            </div>

            <div class="container-contact1-form-btn">
                <button class="contact1-form-btn">
                    <span>
                        Send Email
                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!--===============================================================================================-->
<script src="ContactFrom_v1/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="ContactFrom_v1/vendor/bootstrap/js/popper.js"></script>
<script src="ContactFrom_v1/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="ContactFrom_v1/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="ContactFrom_v1/vendor/tilt/tilt.jquery.min.js"></script>
<script >
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-23581568-13');
</script>

<!--===============================================================================================-->
<script src="ContactFrom_v1/js/main.js"></script>

</body>
</html>
