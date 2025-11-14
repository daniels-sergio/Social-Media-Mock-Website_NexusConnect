<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['username']) || !isset($_GET['username'])) { // Changed from 'user' to 'username'
    exit(json_encode([]));
}

$current_user = mysqli_real_escape_string($conn, $_SESSION['username']);
$other_user = mysqli_real_escape_string($conn, $_GET['username']); // Changed from 'user' to 'username'

$sql = "SELECT m.*, u.profile_picture, u.full_name 
        FROM messages m 
        JOIN users u ON m.sender_id = u.username
        WHERE (m.sender_id = ? AND m.receiver_id = ?) 
        OR (m.sender_id = ? AND m.receiver_id = ?)
        ORDER BY m.timestamp ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $current_user, $other_user, $other_user, $current_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = [
        'sender' => $row['sender_id'],
        'message' => htmlspecialchars($row['message']),
        'timestamp' => $row['timestamp'],
        'profile_picture' => $row['profile_picture'] ?? 'default.jpg',
        'full_name' => $row['full_name']
    ];
}

header('Content-Type: application/json');
echo json_encode($messages);

mysqli_close($conn);
?>