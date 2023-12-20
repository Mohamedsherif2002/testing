<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    login-page{
        background-color:black;
    }
    </style>
</head>
<?php
ob_start();
require_once('includes/load.php');

?>

<?php
// include_once('layouts/header.php');

// Include necessary files for database connection and functions
require_once('includes/database.php');
require_once('includes/functions.php');

// Define a function to check if a username exists in the database
function isUsernameExists($username, $conn) {
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }

    return mysqli_num_rows($result) > 0;
}

// Define a function to check if an email exists in the database
function isEmailExists($email, $conn) {
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }

    return mysqli_num_rows($result) > 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register"])) {
        // Retrieve and sanitize user inputs
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
        $username = mysqli_real_escape_string($conn, $_POST["username"]);
        $gender = mysqli_real_escape_string($conn, $_POST["gender"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        $confirm_password = mysqli_real_escape_string($conn, $_POST["check_password"]);

        // Check if the username is already in use
        if (isUsernameExists($username, $conn)) {
            $msg = "Username already exists.";
        } else {
            // Check if the email is already registered
            if (isEmailExists($email, $conn)) {
                $msg = "Email already registered.";
            } else {
                // Ensure all fields are filled
                if (empty($name) || empty($last_name) || empty($username) || empty($gender) || empty($email) || empty($password) || empty($confirm_password)) {
                    $msg = "All fields are required.";
                } else {
                    // Compare password and confirm password
                    if ($password !== $confirm_password) {
                        $msg = "Password and Confirm Password do not match.";
                    } else {
                        // Hash the password
                        $hashed_password = sha1($password);

                        // Insert the user data into the database (excluding image)
                        $query = "INSERT INTO users (name, lname, username, gender, email, password) VALUES ('$name', '$last_name', '$username', '$gender', '$email', '$hashed_password')";

                        if (mysqli_query($conn, $query)) {
                            $msg = "Registration successful. You can now log in.";
                        } else {
                            $msg = "Error: " . mysqli_error($conn);
                        }

                        // Process file upload (profile picture) if an image is provided
                        if (!empty($_FILES["profile_picture"]["tmp_name"])) {
                            $target_dir = "uploads/";
                            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                            // Check file type (you can add more allowed types as needed)
                            $allowedExtensions = ["jpg", "jpeg", "png", "gif"];

                            if (in_array($imageFileType, $allowedExtensions)) {
                                // Upload the file
                                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                                    // Update the user's profile picture in the database
                                    $updateQuery = "UPDATE users SET image = '$target_file' WHERE username = '$username'";

                                    if (!mysqli_query($conn, $updateQuery)) {
                                        $msg = "Error updating profile picture: " . mysqli_error($conn);
                                    }
                                } else {
                                    $msg = "Sorry, there was an error uploading your file.";
                                }
                            } else {
                                $msg = "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
                            }
                        }
                    }
                }
            }
        }
    }
}
?>

<?php
if (isset($msg)) {
    echo '<div class="alert alert-dismissible ';
    echo ($msg === "Registration successful. You can now log in.") ? 'alert-success' : 'alert-danger';
    echo '">';
    echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    echo "<strong>";
    echo ($msg === "Registration successful. You can now log in.") ? 'Success!' : 'Error!';
    echo "</strong> {$msg}";
    echo '</div>';
}
?>




<div class="login-page" style="width: 70%;height:10%; margin-left: 15%;">
    <div class="text-center">
        <h1>Registration Panel</h1>
    </div>
    <form method="post"  class="clearfix" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-4 pb-2">
                <div class="form-outline">
                    <label class="form-label" for="name">First Name</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" id="name" name="name" class="form-control form-control-lg" required />
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4 pb-2">
                <div class="form-outline">
                    <label class="form-label" for="last_name">Last Name</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" id="last_name" name="last_name" class="form-control form-control-lg" required />
                    </div>
                </div>
            </div>
        </div>
<br>

        <div class="form-group">
            <label for="username" class="control-label">Username</label>
            <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required />
                    </div>
        </div>

        <div class="mb-4 py-2">
    <label for="username" class="control-label">Gender : </label>
    <div class="input-group">
        <div class="form-check form-check-inline mb-0">
            <input class="form-check-input" type="radio" name="gender" id="femaleGender" value="Female" required />
            <label class="form-check-label" for="femaleGender">Female</label>
        </div>
        <div class="form-check form-check-inline mb-0">
            <input class="form-check-input" type="radio" name="gender" id="maleGender" value="Male" required />
            <label class="form-check-label" for="maleGender">Male</label>
        </div>
    </div>
</div>


<br>


        <div class="form-group">
            <label for="email" class="control-label">E-mail</label>
            <div class="input-group">
                        <span class="input-group-addon"><i class="fas fa fa-envelope"></i></span>
                        <input type="email" id="email" name="email" class="form-control" placeholder="user@gmail.com" required />
                    </div>
        </div>
        <div class="form-group">
            <label for="password" class="control-label">Password</label>
            <div class="input-group">
                        <span class="input-group-addon"><i class="fas fa fa-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" minlength="8" maxlength="16" required />
                    </div>
        </div>
        <div class="form-group">
            <label for="check_password" class="control-label">Confirm Password</label>
            <div class="input-group">
                        <span class="input-group-addon"><i class="fas fa fa-lock"></i></span>
                        <input type="password" id="check_password" name="check_password" class="form-control" placeholder="Confirm Password" minlength="8" maxlength="16" required />
                    </div>
        </div>
        
        <br>
        <div class="form-group">
            <button type="submit" class="btn btn-success" style="border-radius: 6%;" name="register">Register</button>
        </div>
    </form>
    <div class="text-center">
        <a href="index.php">ALREADY HAVE AN ACCOUNT ?</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

