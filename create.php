<?php
include 'config.php';

session_start();

// Simple authentication
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_SESSION['user'];
    $image = $_FILES['image']['name'];

    // Upload image if exists
    if ($image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // Insert post into database
    $sql = "INSERT INTO posts (title, content, author, image) VALUES ('$title', '$content', '$author', '$target_file')";
    if ($conn->query($sql) === TRUE) {
        echo "New post created successfully!";
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Post</title>
    <style>
        /* Internal CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            padding: 20px;
        }
        .form-group {
            margin: 10px 0;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }
        .button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<header>
    <h1>Create New Post</h1>
</header>

<div class="container">
    <form action="create.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea name="content" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" name="image">
        </div>
        <button type="submit" class="button">Create Post</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
