<?php include_once('includes/load.php'); ?>

<?php
$req_fields = array('username', 'password');
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if (empty($errors)) {
    $login_result = authenticate_v2($username, $password);

    if ($login_result !== null && isset($login_result['id'])) {
        // User login successful
        $session->login($login_result['id']);
        updateLastLogIn($login_result['id']);

        if ($login_result['user_level'] === '1') {
            $session->msg("s", "Hello " . $login_result['username'] . ", Welcome to OSWA-INV.");
            redirect('admin.php', false);
        } elseif ($login_result['user_level'] === '2') {
            $session->msg("s", "Hello " . $login_result['username'] . ", Welcome to OSWA-INV.");
            redirect('special.php', false);
        } else {
            $session->msg("s", "Hello " . $login_result['username'] . ", Welcome to OSWA-INV.");
            redirect('home.php', false);
        }
    } else {
        // User login failed, display appropriate error message
        if ($login_result === null) {
            $session->msg("d", "Your account is deactivated.");
        } elseif ($login_result === "Invalid password") {
            $session->msg("d", "Sorry, Username/Password incorrect.");
        } elseif ($login_result === "User not found") {
            $session->msg("d", "User not found.");
        }

        redirect('index.php', false);
    }
} else {
    $session->msg("d", $errors);
    redirect('login_v2.php', false);
}

function authenticate_v2($username, $password) {
    global $db;

    $username = $db->escape($username);
    $password = $db->escape($password);

    // Query the database to get user information
    $query = "SELECT id, username, password, user_level, status FROM users WHERE username = '{$username}' LIMIT 1";
    $result = $db->query($query);

    if ($db->num_rows($result) == 1) {
        $user = $db->fetch_assoc($result);

        // Add debugging statements here
        echo "User found in the database<br>";
        echo "Status: " . $user['status'] . "<br>";

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Check the user's status
            if ($user['status'] == 0) {
                return null; // Account is deactivated
            } else {
                return $user; // Valid user
            }
        } else {
            return "Invalid password"; // Incorrect password
        }
    } else {
        return "User not found"; // User not found
    }
}
?>
