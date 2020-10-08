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
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- jquery ui -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- ajax -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />

    <link rel="stylesheet" href="/spin/css/feed.css">

    
    
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <!-- jquery ui -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- ajax popper-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- bootstrap js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    
    <title>Pending Requests</title>
</head>
<body>


    <div id="mySidebar" class="sidebar">
        <div class="logo ">
            <img src="/spin/images/defaultprofile.png" style="width:190px;height:150px;" alt="">
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
        <div class="container2">
            <?php
                $host = "localhost";
                $user = "root";
                $password = "";
                $database = "spin";
                $conn = mysqli_connect($host, $user, $password, $database);
                if ($conn->connect_error)
                    die("connection failed: " . $conn->connect_error);
                $stmt = $conn->prepare("select * from follow_requests, user where user_id_2 = ? and user.user_id=follow_requests.user_id_1");
                $stmt->bind_param("s", $user_id);
                $user_id = $_SESSION['user_id'];
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<div id ='".$row['user_id']."'>";
                    echo $row['username'];
                    echo "<br>".$row['firstname']." ".$row['lastname'];
                    echo ' <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row['profile_photo']) . '" class="d-block" height=300 />';
                    echo "<p>".$row['bio']."</p>";
                    echo "<br><br>";
                    echo "
                        <button class='".$row['user_id']."' onclick='accept(event)'>Accept</button>
                        <button class='".$row['user_id']."' onclick='reject(event)'>Reject</button>
                    </div>";
                }
            ?>
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
        function accept(e) {
            // console.log(e.target.className);

            params="mode=accept&id="+e.target.className;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "acceptdeletereq.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            xhr.onload = function () {
                if (this.status == 200) {
                // console.log(this.responseText);
                }
            };
            document.getElementById(e.target.className).remove();

            xhr.send(params);
        }
        function reject(e) {
            // console.log(e.target.className);

            params="mode=reject&id="+e.target.className;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "acceptdeletereq.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            xhr.onload = function () {
                if (this.status == 200) {
                // console.log(this.responseText);
                }
            };
            document.getElementById(e.target.className).remove();


            xhr.send(params);
        }
    </script>

</body>
</html>