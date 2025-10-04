<?php
$servername = "localhost";
$username = "uatngenulqgbr";  // Change this to your database username
$password = "htqpbfgokcg3";  // Change this to your database password
$dbname = "dbgm3xciqrvxsv";  // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
