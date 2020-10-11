<?php
    session_start();
    if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_GET['user_profile']))) {
        header("Location: /spin/login/login.php");
        exit();
    }
    if ($_SESSION['user_id']==$_GET['user_profile']) {
        header ("Location: /spin/home/myposts.php");
        exit();
    }
    require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/dbConnection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="/spin/home/feed.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <title>User ID</title>
</head>
<body>
    <?php
        require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/sidebar.php';
        require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/navbar.php';
    ?>
    <?php
        $stmt = $conn->prepare("select * from  user where user_id=?");
        $stmt->bind_param("s", $user_id);
        $user_id = $_GET['user_profile'];
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
            
        
        
            $stmt4 = $conn->prepare("select * from  follow_requests where user_id_1 = ? and user_id_2=?");
            $stmt4->bind_param("ss", $user_id_1, $user_id_2);
            $stmt4->execute();
            $result4 = $stmt4->get_result();
            if (mysqli_num_rows($result2))
            {
                echo "<button id = 'requestButton' class='requested'>Unfollow</button>";
            }
            else if (mysqli_num_rows($result4)) {
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
        else {
            echo "No such user exists";
        }
    ?>
    <?php
        $stmt9 = $conn->prepare("select * from followers where user_id_1=? and user_id_2=?");
        $stmt9->bind_param("ss", $user_id_1, $user_id_2);
        $stmt9->execute();
        $result9 = $stmt9->get_result();
        if (mysqli_num_rows($result9) || $row['privacy'] == 'open') {
            echo "<h1>Posts</h1>";
            $following=true;
        }else {
            $following=false;
        }
    ?>
    <div id="main">
        <div id="mypostsarea"></div>
        <div id="loader" class="loader">
            <!-- loading css animation -->
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>
    
    <script>
        var visible = false;

        function toggleNav() {
            if (!visible) {
                document.getElementById("mySidebar").style.width = "250px";
                document.getElementById("main").style.marginLeft = "250px";
            } else {
                document.getElementById("mySidebar").style.width = "0";
                document.getElementById("main").style.marginLeft = "0";
            }
            visible = !visible;
        }
    </script>
    <script>
        var state = true;
        var likes = 0;


        function likeme(event) {

            var post_id = event.currentTarget.name;
            // console.log(event.currentTarget.children[1].innerHTML);
            var like_count=event.currentTarget.children[1]
            // console.log('actually '+like_count);
            document.getElementById(post_id).classList.toggle("liked");
            if ($("#" + post_id).hasClass("liked")) {
                state = true;
                event.currentTarget.children[1].innerHTML = parseInt(event.currentTarget.children[1].innerHTML) + 1
            } else {
                state = false;
                event.currentTarget.children[1].innerHTML = parseInt(event.currentTarget.children[1].innerHTML) - 1
            }
            // console.log(post_id);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/spin/home/likes.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            var params = "state=" + state + "&post_id=" + post_id;
            // console.log(params);
            xhr.onload = function(event) {
                if (this.status == 200) {

                    // console.log(this.responseText);
                    if (this.responseText=="shaana") {
                        alert("chal hatt lombdi");
                        // console.log(like_count);
                        like_count.innerHTML = parseInt(like_count.innerHTML) - 1
                        // console.log(like_count);
                    }
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
            xhr.open("POST", "/spin/posts/sessioncreate.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            var params = "start=" + start + "&post_id=" + post_id;
            console.log(params);
            xhr.onload = function(event) {
                if (this.status == 200) {
                    console.log(this.responseText);
                    window.location = "/spin/posts/postfocus.php";
                }
            }
            xhr.send(params);

        }
    </script>
    
    <?php
        if ($following) {
            echo "<script src='userprofile.js'></script>";
        }
    ?>
    
</body>
</html>