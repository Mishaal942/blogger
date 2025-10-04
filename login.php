<?php
include 'config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start the session and set user information
        $_SESSION['user'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Internal CSS */
        body {
            font-family: Arial, sans-serif;
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
            width: 300px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .form-group {
            margin: 10px 0;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            width: 100%;
            text-decoration: none;
            border-radius: 5px;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Login</h1>
</header>

<div class="container">
    <?php
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="button">Login</button>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>
