
<?php
// difference between this file and get chat is this fetches the chats that have already been established by sending a message
session_start();
require_once("database.php");

//This line is necessary because the file is returning JSON content not standard HTML
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode([]);
    exit();
}

//Sets user
$current_user = $_SESSION['username'];

// Get unique chat partners
$sql = "SELECT DISTINCT 
            u.username,
            u.full_name,
            u.profile_picture
        FROM messages m
        JOIN users u ON (u.username = m.sender_id OR u.username = m.receiver_id)
        WHERE (? IN (m.sender_id, m.receiver_id)) 
          AND u.username != ?
        ORDER BY m.timestamp DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $current_user, $current_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$chats = [];
while ($row = mysqli_fetch_assoc($result)) {
    $other_user = $row['username'];
    
    // Get last message for this conversation
    $msg_sql = "SELECT message, timestamp 
                FROM messages 
                WHERE (? IN (sender_id, receiver_id)) 
                  AND (? IN (sender_id, receiver_id))
                ORDER BY timestamp DESC 
                LIMIT 1";
    
    $msg_stmt = mysqli_prepare($conn, $msg_sql);
    mysqli_stmt_bind_param($msg_stmt, "ss", $current_user, $other_user);
    mysqli_stmt_execute($msg_stmt);
    $msg_result = mysqli_stmt_get_result($msg_stmt);
    $last_msg = mysqli_fetch_assoc($msg_result);
    
    $chats[] = [
        'username' => $row['username'],
        'full_name' => $row['full_name'],
        'profile_picture' => $row['profile_picture'] ?? 'default.jpg',
        'last_message' => $last_msg['message'] ?? 'Click to view conversation',
        'last_message_time' => $last_msg['timestamp'] ?? date('Y-m-d H:i:s'),
        'unread_count' => 0
    ];
    
    mysqli_stmt_close($msg_stmt);
}

// Removes any potential duplicates
$unique_chats = [];
foreach ($chats as $chat) {
    $unique_chats[$chat['username']] = $chat;
}

echo json_encode(array_values($unique_chats));
mysqli_close($conn);
?>