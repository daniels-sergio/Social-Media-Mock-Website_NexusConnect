<?php
session_start();

// Unset all session variables,this logic helps to stop usersfrom bypassing the login page by changing the url without logging in
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session so that the user needs to login again to access their profile
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>