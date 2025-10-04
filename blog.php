<?php
include 'config.php';

if (isset($_GET['id'])) {
    // Get the blog ID from the URL parameter
    $blog_id = $_GET['id'];

    // Prepare the SQL query to fetch the blog with the corresponding ID
    $sql = "SELECT * FROM blogs WHERE id = ?";
    
    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $blog_id); // Bind the blog ID as an integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Blog found, fetch the data
            $blog = $result->fetch_assoc();
        } else {
            // No blog found with the given ID
            $error_message = "Blog not found!";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // SQL preparation failed
        $error_message = "Failed to prepare the query!";
    }
} else {
    // No blog ID in the URL
    $error_message = "Invalid blog ID!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h1 {
            font-size: 24px;
        }
        .content {
            margin-top: 20px;
        }
        .content img {
            width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>Blog Post</h1>
</header>

<div class="container">
    <?php
    if (isset($error_message)) {
        echo "<p class='error'>$error_message</p>";
    } else {
        // Display the blog content
        echo "<h1>" . htmlspecialchars($blog['title']) . "</h1>";
        echo "<p><strong>Author:</strong> " . htmlspecialchars($blog['author']) . "</p>";
        echo "<div class='content'>" . nl2br(htmlspecialchars($blog['content'])) . "</div>";
        
        if (!empty($blog['image'])) {
            echo "<img src='" . htmlspecialchars($blog['image']) . "' alt='Blog Image'>";
        }
    }
    ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
