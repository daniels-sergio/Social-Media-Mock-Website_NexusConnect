<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$response = ['status' => 'error', 'message' => ''];

// Handle profile picture upload
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['profile_picture']['name'];
    $filetype = pathinfo($filename, PATHINFO_EXTENSION);
    
    if (in_array(strtolower($filetype), $allowed)) {
        $newname = uniqid() . '.' . $filetype;
        $uploadPath = 'uploads/' . $newname;
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
            $sql = "UPDATE users SET profile_picture = ? WHERE username = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $newname, $_SESSION['username']);
            mysqli_stmt_execute($stmt);
            $_SESSION['profile_picture'] = $newname;
        }
    }
}

// Update other user information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    $sql = "UPDATE users SET full_name = ?, email = ?, username = ? WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $fullname, $email, $username, $_SESSION['username']);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['full_name'] = $fullname;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        $response['status'] = 'success';
        $response['message'] = 'Profile updated successfully';
    }
    
    // Update password if user changes it in settings
    if (!empty($_POST['new_password'])) {
       $hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $hash, $_SESSION['username']);
        mysqli_stmt_execute($stmt);
    }
}

header('Location: Index.php');
exit;
?>
