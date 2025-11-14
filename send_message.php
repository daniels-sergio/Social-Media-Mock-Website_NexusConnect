<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['username']) || !isset($_POST['receiver']) || !isset($_POST['message'])) {
    exit(json_encode(['status' => 'error', 'message' => 'Invalid request']));
}

$sender = mysqli_real_escape_string($conn, $_SESSION['username']);
$receiver = mysqli_real_escape_string($conn, $_POST['receiver']);
$message = mysqli_real_escape_string($conn, $_POST['message']);

$sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sss", $sender, $receiver, $message);

$response = ['status' => 'error'];
if (mysqli_stmt_execute($stmt)) {
    $response['status'] = 'success';
}

header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($conn);
?>
