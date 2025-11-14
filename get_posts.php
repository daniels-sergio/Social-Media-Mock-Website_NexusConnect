<?php
session_start();
require_once("database.php");

$sql = "SELECT p.*, u.full_name, u.profile_picture 
        FROM posts p 
        JOIN users u ON p.username = u.username 
        ORDER BY p.created_at DESC 
        LIMIT 20";

$result = mysqli_query($conn, $sql);
$posts = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Check if image exists
    if ($row['image_path'] && !file_exists(__DIR__ . '/uploads/posts/' . $row['image_path'])) {
        $row['image_path'] = null;
    }
    
    $posts[] = [
        'id' => $row['post_id'],
        'content' => htmlspecialchars($row['content']),
        'username' => $row['username'],
        'full_name' => $row['full_name'],
        'profile_picture' => $row['profile_picture'],
        'created_at' => $row['created_at'],
        'post_type' => $row['post_type'],
        'image_path' => $row['image_path']
    ];
}

header('Content-Type: application/json');
echo json_encode($posts);
mysqli_close($conn);
?>
