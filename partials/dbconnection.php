<?php
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]))) {
    header("Location: /spin/login/login.php");
    exit();
}
$host = "localhost";
$user = "root";
$password = "";
$database = "spin";
$conn = mysqli_connect($host, $user, $password, $database);
if ($conn->connect_error)
    die("connection failed: " . $conn->connect_error);
?>
