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
    
    <title>Find Friends</title>
</head>
<body>
    <div id="mySidebar" class="sidebar">
        <div class="logo ">
            <img src="/spin/images/defaultprofile.png" style="width:190px;height:150px;" alt="">
        </div>
        <a href="#">My Posts</a>
        <a href="#">My Friends</a>
        <a href="#">Pending Requests</a>
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
        <div class="find-top">
    <h2 style='color:black;'>Search for other users!</h2>
    <br><br>
    <form method="post">
        <div class="autocomplete-container">
            <input  type="text" id="searchbox" name="searchbox" placeholder="start typing">
        </div>
        <input type="submit" name="submit">
    </form>


    <?php
        if (isset($_POST['submit']) && $_POST['searchbox']!='' ) {
        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "spin";
        $conn = mysqli_connect($host, $user, $password, $database);
        if ($conn->connect_error)
            die("connection failed: " . $conn->connect_error);
        $stmt = $conn->prepare("select * from  user where username=?");
        $stmt->bind_param("s", $username);
        $username = $_POST['searchbox'];
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result)) {
            $row = $result->fetch_assoc();
            echo '
                <h1>'.$row['username'].'</h1>
                <h3>'.$row['firstname'].' '.$row['lastname'].'</h3>
                <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row['profile_photo']) . '" class="d-block" height=300 />
                <p>'.$row['bio'].'</p>';
            $stmt2 = $conn->prepare("select * from  followers where user_id_1 = ? and user_id_2=?");
            $stmt2->bind_param("ss", $user_id_1, $user_id_2);
            $user_id_1 = $_SESSION['user_id'];
            $user_id_2 = $row['user_id'];
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if (mysqli_num_rows($result2)) {
                echo "<p>You already follow ".$row['firstname']."!</p></div>";
                $stmt3 = $conn->prepare("select * from  post where user_id = ?");
                $stmt3->bind_param("s", $user_id_2);
                $stmt3->execute();
                $result3=$stmt3->get_result();
                $k=0;
                while ($row3 = $result3->fetch_assoc()) {
                    echo
                        "<div class='posts-style'>
                    <ul>
                    <li>" . $row3['post_id'] . "</li>
                    <li>" . $row3['user_id'] . "</li>
                    <li>" . $row3['caption'] . "</li>" .
                            "<li>";
                    $sql5 = "SELECT image FROM pictures WHERE post_id=" . $row3['post_id'];
                    $result5 = mysqli_query($conn, $sql5);
                    echo
                        '<div id="carousel' . $k . '" class="carousel slide" data-interval="false" data-wrap="false">
                            <ol class="carousel-indicators">';
                    if (mysqli_num_rows($result5) > 1) {
                        for ($i = 0; $i < mysqli_num_rows($result5); $i++) {
                            echo "<li data-target='#carousel$k' data-slide-to='" . ($i + 1) . "'";
                            if ($i == 0) {
                                echo " class='active'";
                            }
                            echo "></li>";
                        }
                    }
                    echo
                        '</ol>
                        <div class="carousel-inner">';
                    $j = 0;
                    while ($row5 = mysqli_fetch_assoc($result5)) {
                        echo '<div class="carousel-item';
                        if ($j == 0) {
                            echo " active";
                            $j += 1;
                        }
                        echo '">
                            <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row5['image']) . '" class="d-block" height=300 />
                            </div>';
                    }
                    echo '</div>';
                    if (mysqli_num_rows($result5) > 1) {
                        echo '<a class="carousel-control-prev" href="#carousel' . $k . '" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel' . $k . '" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>';
                    }

                    echo '</div>
                        </li>
                        </ul>
                        </div><hr>';
                    $k+=1;
                }
            }else
            {
                $stmt4 = $conn->prepare("select * from  follow_requests where user_id_1 = ? and user_id_2=?");
                $stmt4->bind_param("ss", $user_id_1, $user_id_2);
                $stmt4->execute();
                $result4 = $stmt4->get_result();
                if (mysqli_num_rows($result4)) {
                    echo "<button id = 'requestButton' class='requested'>Cancel Request</button>";
                }else {
                    echo "<button id = 'requestButton'>Send request</button>";
                }
                echo "</div>";
                ?>
                    <script>
                        if ($('#requestButton').text() == 'Cancel Request') {
                            $('#requestButton').css("background-color", "lightgreen")
                        }
                        $('#requestButton').click(function(){
                            let params="code=";
                            if ($(this).text() == 'Cancel Request') {
                                $(this).css("background-color", "green")
                                $(this).text("Send request");
                                params+="remove"
                            }else
                            {
                                $(this).css("background-color", "lightgreen")
                                $(this).text("Cancel Request");
                                params+="add"
                            }
                            params+="&target="+<?php echo $user_id_2?>

                            const xhr = new XMLHttpRequest();
                            xhr.open("POST", "managerequests.php", true);
                            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            
                            xhr.onload = function () {
                                if (this.status == 200) {
                                // console.log(this.responseText);
                                }
                            };


                            xhr.send(params);

                        });
                    </script>
                <?php
            }
        }else {
            echo "No such user exists";
        }
    }
    ?>
    </div>
    <script type="text/javascript" src="/spin/js/findfriends.js"></script>
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
</body>
</html>

<!-- 
    things left
    redirect to login only if session variables are not set, redirect to deef for post not set
    add dynamic checking for existing username and password
    add logout
    close open php tags
    add col names to insert queries
 -->