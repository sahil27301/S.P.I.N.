<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "spin";
$conn = mysqli_connect($host, $user, $password, $database);
if ($conn->connect_error) {
    die("connection failed: " . $conn->connect_error);

    $sql = "SELECT * from posts"; //We need to change this query since friends can see their friends posts only
    $result = mysqli_query($conn, $sql);
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    echo json_encode($posts);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/feed.css">
    <title>S.P.I.N</title>
</head>

<body>
    <nav>
        <div class="logo ">
            <img src="images/defaultprofile.png" style="width:190px;height:150px;" alt="">
        </div>
        <ul>
            <li><a href="#">My Posts</a></li>
            <li><a href="#">My Friends</a></li>
            <li><a href="#">Pending Requests</a></li>
            <li><a href="#">Upload a Post!</a></li>
        </ul>
    </nav>
    <header class="navbar sticky-top">
        <button class="toggle navbar sticky-top" id="toggle">
            <i class="fa fa-bars fa-2x"></i>
        </button>
        <h2>S.P.I.N</h2>
        <button id="fetchpost">Fetch Posts</button>
    </header>
    <!-- <div class="container" style="background-color: red;">
        <div class="post-container">
            <h2>feed</h2>
        </div>
    </div>
    <div class="container" style="background-color: red;">
        <div class="post-container">
            <h2>feed</h2>
        </div>
    </div>
    <div class="container" style="background-color: red;">
        <div class="post-container">
            <h2>feed</h2>
        </div>
    </div> -->
    <div class="loader">
        <!-- loading css animation -->
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>
    <script src="feed.js"></script>
</body>

</html>
