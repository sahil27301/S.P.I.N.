<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_POST['code']) && isset($_POST['target']))) {
    header("Location: login.php");
    exit();
}
$host = "localhost";
$user = "root";
$password = "";
$database = "spin";
$conn = mysqli_connect($host, $user, $password, $database);
if ($conn->connect_error)
    die("connection failed: " . $conn->connect_error);

$user_id_1 = $_SESSION['user_id'];
$user_id_2 = $_POST['target'];

if ($_POST['code'] == 'add') {
    $stmt=$conn->prepare("insert into follow_requests(user_id_1, user_id_2) values(?,?)");
    $stmt->bind_param("ss", $user_id_1, $user_id_2);
    $stmt->execute();
    // if ($stmt) {
    //     echo "Added succesfully";
    // }else {
    //     echo "fail";
    // }
}elseif ($_POST['code'] == 'remove') {
    $stmt=$conn->prepare("delete from follow_requests where user_id_1 = ? and user_id_2 = ?");
    $stmt->bind_param("ss", $user_id_1, $user_id_2);
    $stmt->execute();
    // if ($stmt) {
    //     echo "Deleted succesfully";
    // }else {
    //     echo "fail";
    // }
}
?>