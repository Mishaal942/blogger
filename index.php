<?php
include 'config.php';

// Fetch all blogs from the database, ordered by latest first
$sql = "SELECT * FROM blogs ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Homepage</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        h1, h2 {
            margin: 0 0 10px;
        }

        .blog-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 0 15px;
        }

        /* Blog Post Styling */
        .blog {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .blog h2 {
            font-size: 24px;
            color: #333;
        }

        .blog p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .blog img {
            max-width: 100%;
            height: auto;
            margin: 15px 0;
            border-radius: 5px;
        }

        .blog a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .blog a:hover {
            background-color: #0056b3;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .blog-container {
                padding: 0 10px;
            }

            .blog h2 {
                font-size: 20px;
            }

            .blog p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to My Blogger Clone</h1>
    </header>

    <div class="blog-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='blog'>";
                echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
                echo "<p><strong>Author:</strong> " . htmlspecialchars($row['author']) . "</p>";
                echo "<p>" . substr(htmlspecialchars($row['content']), 0, 150) . "...</p>";

                if (!empty($row['image']) && file_exists("uploads/" . $row['image'])) {
                    echo "<img src='uploads/" . htmlspecialchars($row['image']) . "' alt='Blog Image'>";
                }

                echo "<a href='view_blog.php?id=" . $row['id'] . "'>Read More</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No blogs available.</p>";
        }
        ?>
    </div>
</body>
</html>
