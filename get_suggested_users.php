<?php
session_start();
require_once("database.php");

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$current_user = $_SESSION['username'];

try {
    // Get 3 random users that aren't the current user
    $sql = "SELECT username, full_name, profile_picture 
            FROM users 
            WHERE username != ? 
            ORDER BY RAND() 
            LIMIT 3";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $current_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $users = [];
    while ($row = mysqli_fetch_assoc($result)) { //Displays there user information and when you click on them it takes you to their profile
        $users[] = [
            'username' => $row['username'],
            'full_name' => $row['full_name'] ?? $row['username'],
            'profile_picture' => $row['profile_picture'] ?? 'default.jpg'
        ];
    }

    echo json_encode($users);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]); //WHen the users cannot be fetched because of an issue with the database
}

mysqli_close($conn);
?>