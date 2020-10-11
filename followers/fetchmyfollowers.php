<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "spin";
$conn = mysqli_connect($host, $user, $password, $database);
if ($conn->connect_error)
    die("connection failed: " . $conn->connect_error);
if (isset($_POST['start']) && isset($_POST['limit'])) {
    $start = mysqli_real_escape_string($conn, $_POST['start']);
    $limit = mysqli_real_escape_string($conn, $_POST['limit']);
}
$start = mysqli_real_escape_string($conn, $_POST['start']);
$limit = mysqli_real_escape_string($conn, $_POST['limit']);
$user_id = $_SESSION['user_id'];
$sql = "SELECT username,profile_photo from user where user_id in (select user_id_2 from followers where user_id_1='$user_id') LIMIT $limit OFFSET $start"; //We need to change this query since friends can see their friends posts only
$result = mysqli_query($conn, $sql);

$k = 0;

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $username = $row['username'];
        $sqlx = "SELECT profile_photo from user where username='$username'";
        $rezy = mysqli_query($conn, $sqlx);
        $rowxx = $rezy->fetch_assoc();
        echo
            "<div class='posts-style-for-followers'>
            <div>
            <img src='data:image/jpeg;charset=utf8;base64," . base64_encode($rowxx['profile_photo']) . "' class='d-block profile-pic-in-my-followers' height=300/>
            <h3 class='post-username'>" . $row['username'] . "</h3>
            </div>";
    }
} else {
    echo "Reached";
}
