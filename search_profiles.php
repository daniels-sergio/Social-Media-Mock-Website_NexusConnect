<?php
session_start();
require_once("database.php"); //This line allows for a "connection" to be made with the database

$query = $_GET['q'] ?? '';
$query = mysqli_real_escape_string($conn, $query);

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT username, full_name, profile_picture 
        FROM users 
        WHERE (username LIKE '%$query%' OR full_name LIKE '%$query%')
        AND username != '{$_SESSION['username']}'
        LIMIT 8";

$result = mysqli_query($conn, $sql);
$users = [];
//Live search functionality - how it fetches the available rows in the database ,this applies to the first search bar in the index page
while($row = mysqli_fetch_assoc($result)) {
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