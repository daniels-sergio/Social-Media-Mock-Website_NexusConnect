<?php
session_start();
require_once("database.php");

$query = $_GET['q'] ?? '';
$query = mysqli_real_escape_string($conn, $query);

$sql = "SELECT username, full_name, profile_picture 
        FROM users 
        WHERE (username LIKE '%$query%' OR full_name LIKE '%$query%')
        AND username != '{$_SESSION['username']}'
        LIMIT 5";

$result = mysqli_query($conn, $sql);
$users = [];

while($row = mysqli_fetch_assoc($result)) { //logic for searching users profile
    $users[] = [
        'username' => $row['username'],
        'full_name' => $row['full_name'],
        'profile_picture' => $row['profile_picture'] ?? 'default.jpg'
    ];
}

header('Content-Type: application/json');
echo json_encode($users);
mysqli_close($conn);
?>
