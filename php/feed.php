<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]))) {
    header("Location: login.php");
    exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="/spin/css/feed.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
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
            <li><a href="#">Pending Requests</a></li>
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
    <div id="mySidebar" class="sidebar">
        <div class="logo ">
            <img src="/spin/images/defaultprofile.png" style="width:190px;height:150px;" alt="">
        </div>
        <a href="#">My Posts</a>
        <a href="#">My Friends</a>
        <a href="#">Pending Requests</a>
        <a href="loadposts.php">Upload a Post!</a>
    </div>

    <nav class="navbar sticky-top" style="width:100%;z-index:1;">
        <button class="openbtn" onclick="toggleNav()">
            <i class="fa fa-bars fa-2x"></i>
        </button>
        <h2>S.P.I.N</h2>
        <button id="fetchpost">Fetch Posts</button>
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
    <script src="/spin/js/feed.js"></script>
</body>

</html>