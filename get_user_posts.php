<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

// Get username from query parameter
$username = $_GET['username'] ?? null; //We can use get because its not sensitive information we are parsing,so it will show in the URL

if (!$username) {
    echo json_encode([]);
    exit;
}

// Escape the username
$username = mysqli_real_escape_string($conn, $username);

// Fetch user's posts - using username when there are actually posts, meaning that images arent null
$sql = "SELECT p.*, u.username, u.full_name, u.profile_picture,
        CASE 
            WHEN p.image_path IS NOT NULL THEN 'image'
            ELSE 'text'
        END as post_type
        FROM posts p
        JOIN users u ON p.username = u.username
        WHERE u.username = ?
        ORDER BY p.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$posts = [];
while($row = mysqli_fetch_assoc($result)) {
    $posts[] = [
        'post_id' => $row['post_id'],
        'content' => $row['content'],
        'image_path' => $row['image_path'],
        'post_type' => $row['post_type'],
        'created_at' => $row['created_at'],
        'username' => $row['username'],
        'full_name' => $row['full_name'],
        'profile_picture' => $row['profile_picture'] ?? 'default.jpg'
    ];
}

header('Content-Type: application/json');
echo json_encode($posts);
mysqli_close($conn);
?>