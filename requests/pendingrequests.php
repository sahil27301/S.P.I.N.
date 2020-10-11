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
    
    <title>Pending Requests</title>
</head>
<body>


    
    <?php
        require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/sidebar.php';
        require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/navbar.php';
    ?>


    <div id="main">
        <div class="container2">
            <?php
                $stmt = $conn->prepare("select * from follow_requests, user where user_id_2 = ? and user.user_id=follow_requests.user_id_1");
                $stmt->bind_param("s", $user_id);
                $user_id = $_SESSION['user_id'];
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<div id ='".$row['user_id']."'>";
                    echo $row['username'];
                    echo "<br>".$row['firstname']." ".$row['lastname'];
                    echo ' <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row['profile_photo']) . '" class="d-block" height=300 style="margin:auto;"/>';
                    echo "<p>".$row['bio']."</p>";
                    echo "<br><br>";
                    echo "
                        <button class='".$row['user_id']." btn btn-success' onclick='accept(event)'>Accept</button>
                        <button class='".$row['user_id']." btn btn-danger' onclick='reject(event)'>Reject</button>
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

            params="mode=accept&id="+e.target.classList.item(0);

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "acceptdeletereq.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            xhr.onload = function () {
                if (this.status == 200) {
                // console.log(this.responseText);
                }
            };
            document.getElementById(e.target.classList.item(0)).remove();

            xhr.send(params);
        }
        function reject(e) {
            // console.log(e.target.className);

            params="mode=reject&id="+e.target.classList.item(0);

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "acceptdeletereq.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            xhr.onload = function () {
                if (this.status == 200) {
                // console.log(this.responseText);
                }
            };
            document.getElementById(e.target.classList.item(0)).remove();


            xhr.send(params);
        }
    </script>

</body>
</html>