<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['username'])) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
}

$username = $_SESSION['username'];
$content = isset($_POST['content']) ? mysqli_real_escape_string($conn, $_POST['content']) : '';
$post_type = 'text';
$image_path = null;

// Create uploads directory if it doesn't exist
$upload_dir = __DIR__ . '/uploads/posts';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif']; // 
    $filename = $_FILES['image']['name'];
    $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (in_array($filetype, $allowed)) {
        $new_filename = uniqid('post_') . '.' . $filetype;
        $upload_path = $upload_dir . '/' . $new_filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_path = $new_filename;
            $post_type = 'image';
        } else {
            die(json_encode([
                'status' => 'error', 
                'message' => 'Failed to upload image',
                'debug' => error_get_last()
            ]));
        }
    }
}

// Only create post if there's content or an image
if (empty($content) && empty($image_path)) {
    die(json_encode(['status' => 'error', 'message' => 'Post must have content or image']));
}

$sql = "INSERT INTO posts (username, content, post_type, image_path) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $username, $content, $post_type, $image_path);

$response = [];
if (mysqli_stmt_execute($stmt)) {
    $response = ['status' => 'success', 'message' => 'Post created successfully'];
} else {
    $response = ['status' => 'error', 'message' => 'Failed to create post: ' . mysqli_error($conn)];
}

header('Content-Type: application/json');
echo json_encode($response);
mysqli_close($conn);
?>
