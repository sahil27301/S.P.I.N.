<?php  //this php file is used to return the posts where I have passed start and limit parameters via AJAX
$host = "localhost";
$user = "root";
$password = "";
$database = "spin";
$conn = mysqli_connect($host, $user, $password, $database);
if ($conn->connect_error)
    die("connection failed: " . $conn->connect_error);

if (isset($_POST['start'])) {
    $start = mysqli_real_escape_string($conn, $_POST['start']);
    $limit = mysqli_real_escape_string($conn, $_POST['limit']);
    $sql = "SELECT * from post  LIMIT $limit OFFSET $start"; //We need to change this query since friends can see their friends posts only
    $result = mysqli_query($conn, $sql);
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if (mysqli_num_rows($result) > 0) {
        echo json_encode($posts);
    } else
        echo "Reached"; //No more posts left to fetch. Need to add "Reached the end of the feed" statement at the end of the feed
}
