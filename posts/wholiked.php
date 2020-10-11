<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]))) {
    header("Location: /spin/login/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$host = "localhost";
$user = "root";
$password = "";
$database = "spin";
$conn = mysqli_connect($host, $user, $password, $database);
if ($conn->connect_error)
    die("connection failed: " . $conn->connect_error);
if (isset($_POST['post_id'])) {

    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $sqlx = "SELECT username,profile_photo from user inner join likes on likes.user_id=user.user_id where post_id='$post_id'";
    $rez = mysqli_query($conn, $sqlx);
    if (mysqli_num_rows($rez) > 0) {
        while ($row = mysqli_fetch_assoc($rez)) {
            echo "<div style='background-color: #f4f4f4;min-height:70px;margin:40px'>
                <img src='data:image/jpeg;charset=utf8;base64," . base64_encode($row['profile_photo']) .
                " ' class='d-block 'style='width:60px;height:60px;float:left;margin-right:30px' height=300 /><p>" . $row['username'] . "</p>
            </div>";
        }
    } else {
        echo "No one has liked this post yet";
    }
    mysqli_free_result($rez);
}
