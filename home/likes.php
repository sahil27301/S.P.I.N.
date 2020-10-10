<?php
session_start();
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
if (isset($_POST['state']) && isset($_POST['post_id'])) {

    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $user_id = $_SESSION["user_id"];
    // echo $state;
    if ($state == 'true') {
        // echo $state;
        $sql = "INSERT into likes VALUES('$post_id','$user_id')";
        if (mysqli_query($conn, $sql)) {
            echo "Like added";
        } else
            echo "Something went wrong";
    } else if ($state == 'false') {
        $sql = "DELETE from likes where post_id='$post_id' and user_id='$user_id'";
        if (mysqli_query($conn, $sql))
            echo "Like removed";
        else
            echo "Something went wrong while removing the like";
    }
}
