<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]))) {
    header("Location: login.php");
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/spin/css/feed.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <title>S.P.I.N</title>
</head>

<body>
    <!-- <nav>
        <div class="logo ">
            <img src="/spin/images/defaultprofile.png" style="width:190px;height:150px;" alt="">
        </div>
        <ul>
            <li><a href="#">My Posts</a></li>
            <li><a href="#">My Friends</a></li>
            <li><a href="pendingrequests.php">Pending Requests</a></li>
            <li><a href="loadposts.php">Upload a Post!</a></li>
        </ul>
    </nav>
    <header class="navbar sticky-top">
        <button class="toggle navbar sticky-top" id="toggle">
            <i class="fa fa-bars fa-2x"></i>
        </button>
        <h2>S.P.I.N</h2>
        <button id="fetchpost">Fetch Posts</button>
    </header> -->
    <!--  -->
    <!--  -->
    <!-- Sidebar -->



    <!--  -->
    <!--  -->
    <?php
    $user = $_SESSION['user_id'];
    $sqlx = "SELECT profile_photo from user where user_id='$user'";
    $rezx = mysqli_query($conn, $sqlx);
    $row = $rezx->fetch_assoc();

    ?>
    <div id="mySidebar" class="sidebar">
        <div class="logo ">
            <?php
            echo '
                <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row['profile_photo']) . '" class="d-block profile_pic" height=300 />';
            ?>
        </div>
        <a href="#">My Posts</a>
        <a href="#">My Friends</a>
        <a href="pendingrequests.php">Pending Requests</a>
        <a href="loadposts.php">Upload a Post!</a>
        <a href="findfriends.php">Find New Friends</a>
    </div>

    <nav class="navbar sticky-top" style="width:100%;z-index:1;">
        <button class="openbtn" onclick="toggleNav()">
            <i class="fa fa-bars fa-2x"></i>
        </button>
        <h2>S.P.I.N</h2>
        <button id="logout">Logout</button>
    </nav>
    <div id="main">
        <div id="postsarea"></div>
        <div id="loader" class="loader">
            <!-- loading css animation -->
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>
    <script>
        var state = false;

        function toggleNav() {
            if (!state) {
                document.getElementById("mySidebar").style.width = "250px";
                document.getElementById("main").style.marginLeft = "250px";
            } else {
                document.getElementById("mySidebar").style.width = "0";
                document.getElementById("main").style.marginLeft = "0";
            }
            state = !state;
        }
    </script>
    <script>
        var state = true;
        var likes = 0;


        function likeme(event) {

            var post_id = event.currentTarget.name;
            // console.log(event.currentTarget);
            document.getElementById(post_id).classList.toggle("liked");
            if ($("#" + post_id).hasClass("liked")) {
                state = true;
            } else {
                state = false;
            }
            console.log(post_id);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/spin/php/likes.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            var params = "state=" + state + "&post_id=" + post_id;
            console.log(params);
            xhr.onload = function(event) {
                if (this.status == 200) {
                    console.log(this.responseText);
                    location.reload();
                }
            }
            xhr.send(params);
        }
    </script>
    <script>
        function focuss(event) {
            var post_id = event.currentTarget.name;
            var start = event.currentTarget.value;
            console.log(post_id);
            // window.location = "/spin/php/postfocus.php?post_id=" + post_id + "&start=" + start;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/spin/php/sessioncreate.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            var params = "start=" + start + "&post_id=" + post_id;
            console.log(params);
            xhr.onload = function(event) {
                if (this.status == 200) {
                    console.log(this.responseText);
                    window.location = "/spin/php/postfocus.php";
                }
            }
            xhr.send(params);

        }
    </script>
    <script src="/spin/js/feed.js"></script>
</body>

</html>