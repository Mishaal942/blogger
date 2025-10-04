<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM blogs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Blog not found.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($row['title']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="blog-view">
        <h2><?= $row['title'] ?></h2>
        <p><strong>Author:</strong> <?= $row['author'] ?></p>
        <p><?= $row['content'] ?></p>
        <?php if ($row['image']): ?>
            <img src="uploads/<?= $row['image'] ?>" alt="Blog Image">
        <?php endif; ?>
    </div>
</body>
</html>
