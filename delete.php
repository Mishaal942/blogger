<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the post
    $sql = "DELETE FROM posts WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Post deleted successfully!";
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error deleting post: " . $conn->error;
    }
}

$conn->close();
?>
