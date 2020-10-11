<?php
    session_start();
    if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]))) {
    header("Location: /spin/login/login.php");
    exit();
    }
    require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/dbConnection.php';
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

    <link rel="stylesheet" href="/spin/home/feed.css">

    
    
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
    <?php
        require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/sidebar.php';
        require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/navbar.php';
    ?>
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
        $stmt = $conn->prepare("select * from  user where username=?");
        $stmt->bind_param("s", $username);
        $username = $_POST['searchbox'];
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result)) {
            $row = $result->fetch_assoc();
            echo '<a href="/spin/users/userprofile.php?user_profile='.$row["user_id"].'">
                <h1>'.$row['username'].'</h1>
                <h3>'.$row['firstname'].' '.$row['lastname'].'</h3>
                <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row['profile_photo']) . '" class="d-block" height=300 style="margin:auto;" />
                <p>'.$row['bio'].'</p></a>';
            $stmt2 = $conn->prepare("select * from  followers where user_id_1 = ? and user_id_2=?");
            $stmt2->bind_param("ss", $user_id_1, $user_id_2);
            $user_id_1 = $_SESSION['user_id'];
            $user_id_2 = $row['user_id'];
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if (mysqli_num_rows($result2)) {
                echo "<p>You already follow ".$row['firstname']."!</p></div>";
            }else
            {
                $stmt4 = $conn->prepare("select * from  follow_requests where user_id_1 = ? and user_id_2=?");
                $stmt4->bind_param("ss", $user_id_1, $user_id_2);
                $stmt4->execute();
                $result4 = $stmt4->get_result();
                if (mysqli_num_rows($result4)) {
                    echo "<button id = 'requestButton' class='requested'>Cancel Request</button>";
                }else {
                    echo "<button id = 'requestButton'>Send Request</button>";
                }
                echo "</div>";
                ?>
                    <script>
                    reload_required=false;
                    if ($('#requestButton').text() == 'Cancel Request' || $('#requestButton').text() == 'Unfollow') {
                        $('#requestButton').css("background-color", "lightgreen")
                    }
                    $('#requestButton').click(function(){
                        let params="code=";
                        if ($(this).text() == 'Cancel Request') {
                            $(this).css("background-color", "green")
                            $(this).text("Send Request");
                            params+="remove"
                        }else if ($(this).text() == 'Send Request')
                        {
                            $(this).css("background-color", "lightgreen")
                            $(this).text("Cancel Request");
                            params+="add"
                        }
                        else
                        {
                            $(this).css("background-color", "green")
                            $(this).text("Send Request");
                            params+="unfollow"
                            reload_required=true;
                        }
                        params+="&target="+<?php echo $user_id_2?>

                        const xhr = new XMLHttpRequest();
                        xhr.open("POST", "/spin/findusers/managerequests.php", true);
                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhr.onload = function () {
                            if (this.status == 200) {
                                // console.log(this.responseText);
                                if (this.responseText=='open'){
                                    $("#requestButton").css("background-color", "lightgreen")
                                    $("#requestButton").text("Unfollow");
                                }
                            }
                        };
                        xhr.send(params);
                        if (reload_required)
                        {
                            location.reload();
                        }

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
    <script type="text/javascript" src="findfriends.js"></script>
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
    add col names to insert queries********
    check for acct type
    clean autocomplete text?
    feed is showing all posts, check followers
    add partials for db conn, sidebar, navbar
    remove start from postfocus
    FIX ALL PAGE REDIRECTS
    postfocus bracket matching
    check username email live on registration
    add partials for sessions
    add forgot password
    touch events
 -->