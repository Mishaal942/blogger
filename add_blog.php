<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php';

$errors = [];
$success = '';

if (isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
    
    // Validate required fields
    if (empty($title)) $errors[] = "Title is required";
    if (empty($content)) $errors[] = "Content is required";
    if (empty($author)) $errors[] = "Author is required";

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Validate image type
        if (!in_array($filetype, $allowed)) {
            $errors[] = "Only JPG, JPEG, PNG & GIF files are allowed";
        } else {
            // Generate unique filename
            $image = uniqid() . '.' . $filetype;
            $target = "uploads/" . $image;
            
            // Create uploads directory if it doesn't exist
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            // Move and verify upload
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $errors[] = "Error uploading image";
            }
        }
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        $sql = "INSERT INTO blogs (title, content, author, image) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $title, $content, $author, $image);
            if ($stmt->execute()) {
                $success = "Blog post added successfully!";
                // Clear form data
                $title = $content = $author = '';
            } else {
                $errors[] = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Failed to prepare statement";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Blog Post</title>
    <style>
        :root {
            --primary-color: #4a90e2;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f7fa;
        }

        .form-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 500;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="file"] {
            padding: 10px 0;
        }

        textarea {
            min-height: 200px;
            resize: vertical;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        button[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #357abd;
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-error {
            background-color: #fdeaea;
            border: 1px solid #fadbd8;
            color: var(--error-color);
        }

        .alert-success {
            background-color: #eafaf1;
            border: 1px solid #d5f5e3;
            color: var(--success-color);
        }

        .image-preview {
            max-width: 100%;
            margin-top: 10px;
            border-radius: 6px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add a New Blog Post</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" required><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" value="<?php echo isset($author) ? htmlspecialchars($author) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="image">Featured Image</label>
                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                <img id="imagePreview" class="image-preview">
            </div>

            <button type="submit" name="submit">Publish Blog Post</button>
        </form>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
