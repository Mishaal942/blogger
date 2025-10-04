<?php
include 'config.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$sql = "SELECT * FROM posts WHERE author = '" . $_SESSION['user'] . "' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f5f7fb;
            color: #333;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Create New Post Button */
        .btn {
            display: inline-block;
            background-color: #2ecc71;
            color: white;
            padding: 0.8rem 1.5rem;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(46, 204, 113, 0.2);
        }

        .btn:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(46, 204, 113, 0.3);
        }

        /* Post Card Styles */
        .post {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .post:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .post h2 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .post p {
            color: #666;
            margin-bottom: 1.2rem;
            line-height: 1.6;
        }

        /* Action Buttons */
        .btn-edit, .btn-delete {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            margin-right: 0.8rem;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background-color: #3498db;
            color: white;
            box-shadow: 0 2px 5px rgba(52, 152, 219, 0.2);
        }

        .btn-edit:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
            box-shadow: 0 2px 5px rgba(231, 76, 60, 0.2);
        }

        .btn-delete:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }

        /* No Posts Message */
        .dashboard-container > p {
            text-align: center;
            color: #7f8c8d;
            font-size: 1.1rem;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            header h1 {
                font-size: 2rem;
            }

            .dashboard-container {
                padding: 0 1rem;
            }

            .post {
                padding: 1.2rem;
            }

            .btn, .btn-edit, .btn-delete {
                display: block;
                text-align: center;
                margin-bottom: 0.5rem;
            }
        }

        /* Animation for New Posts */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .post {
            animation: fadeIn 0.5s ease-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>My Blogger Dashboard</h1>
    </header>
    <div class="dashboard-container">
        <a href="create.php" class="btn">Create New Post</a>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <h2><?= $row['title'] ?></h2>
                    <p><?= substr($row['content'], 0, 100) ?>...</p>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn-delete">Delete</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts yet. Start creating one!</p>
        <?php endif; ?>
    </div>
</body>
</html>
