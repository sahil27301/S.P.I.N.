<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]))) {
    header("Location: /spin/login/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/dbConnection.php';
$comment_box = '';
$username_of_post = '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/spin/home/feed.css">
    <link href="https://fonts.googleapis.com/css2?family=Hammersmith+One&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <title>S.P.I.N</title>
</head>

<body>
    <?php
    if (isset($_POST['comment_id'])) {
        $comment_id = mysqli_real_escape_string($conn, $_POST['comment_id']);
        $sql_for_deleting_comment = "DELETE from comments where comment_id='$comment_id'";
        $rex = mysqli_query($conn, $sql_for_deleting_comment);
    }

    if (isset($_POST['submit'])) {
        date_default_timezone_set("Asia/Calcutta");
        $current_time = date("Ymd") . date("His");
        $current_time = $current_time . "%";
        $stmt2 = $conn->prepare("select count(*) from comments where comment_id like ?");
        $stmt2->bind_param("s", $current_time);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $row = $result->fetch_assoc();
        $current_time_2 = date("Ymd") . date("His");
        $comment_id = $current_time_2 . str_pad(($row['count(*)'] + 1), 4, "0", STR_PAD_LEFT);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $start = mysqli_real_escape_string($conn, $_POST['start']);
        $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
        $sql = "INSERT into comments(post_id, comment, comment_id, user_id) VALUES('$post_id','$comment','$comment_id','$user_id')";
        $rez = mysqli_query($conn, $sql);
    } else {
        $post_id = $_SESSION['post_id'];
        $start = $_SESSION['start'];
    }

    $sql = "SELECT username,user_id,caption,post_id from post natural join user where post_id='$post_id'";
    $result = mysqli_query($conn, $sql);
    $sql2 = "SELECT image from pictures where post_id='$post_id";
    $res2 = mysqli_query($conn, $sql2);
    ?>
    
    <?php
        require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/sidebar.php';
        require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/navbar.php';
    ?>
    <script>
        var visible = false;

        function toggleNav() {
            if (!visible) {
                document.getElementById("mySidebar").style.width = "250px";
            } else {
                document.getElementById("mySidebar").style.width = "0";
            }
            visible = !visible;
        }
    </script>
    <?php
    $k = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $username = $row['username'];
        $sqlx = "SELECT profile_photo from user where username='$username'";
        $rezy = mysqli_query($conn, $sqlx);
        $rowxx = $rezy->fetch_assoc();
        $username_of_post = $row['username'];
        echo
            "<div class='posts-style'>
            <div>
            <img src='data:image/jpeg;charset=utf8;base64," . base64_encode($rowxx['profile_photo']) . "' class='d-block profile_pic_in_posts' height=300 />
            <h5 class='post-username'>" . $row['username'] . "</h5>
            <p class='post-caption'>" . $row['caption'] . "</p>
            </div>
          <ul>" .
                "<li>";
        $sql2 = "SELECT image FROM pictures WHERE post_id=" . $row['post_id'];
        $result2 = mysqli_query($conn, $sql2);
        $like_id = $row['post_id'];
        $likestatus = "";
        $query_for_like = "SELECT * from likes where user_id='$user_id' and post_id='$like_id'";
        $queryresult = mysqli_query($conn, $query_for_like);
        if (mysqli_num_rows($queryresult) > 0) {
            $likestatus = "liked";
        }
        $query_for_counting = "SELECT COUNT(post_id) as likeCount FROM likes where post_id='$like_id'";
        $result_for_counting = $conn->query($query_for_counting);
        $likecount = $result_for_counting->fetch_assoc();

        echo
            '<div id="carousel' . $k . '" class="carousel slide" data-interval="false" data-wrap="false">
                <ol class="carousel-indicators">';
        if (mysqli_num_rows($result2) > 1) {
            for ($i = 0; $i < mysqli_num_rows($result2); $i++) {
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
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo '<div class="carousel-item';
            if ($j == 0) {
                echo " active";
                $j += 1;
            }
            echo '">
                <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row2['image']) . '" class="d-block" height=300 style="margin:auto;"/>
                </div>';
        }
        echo '</div>';
        if (mysqli_num_rows($result2) > 1) {
            echo '<a class="carousel-control-prev" href="#carousel' . $k . '" role="button" data-slide="prev" style="background-color:lightblue;z-index:0">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel' . $k . '" role="button" data-slide="next" style="background-color:lightblue;z-index:0">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>';
        }

        echo '</>';

        echo
            "</li>
          </ul>
          <div class='postfocus-postfoot-div'><button class='likebtn " . $likestatus . "'id=" . $like_id . " name=" . $like_id . " value= '0' onclick='likeme(event)'><i class='fas fa-thumbs-up fa-2x'></i><h5>" . $likecount['likeCount'] . "</h5></button><button type='button' class='btn btn-info btn-lg modalbtn' onclick='modalclicked()' value=" . $post_id . " data-toggle='modal' data-target='#myModal'>Who liked it?</button></div>
          </div>";
        $k += 1;
    }
    ?>
    <script>

    </script>
    <form action="/spin/posts/postfocus.php" method="POST" id='comment-form'>
        <input type="hidden" name='post_id' value="<?php echo $post_id; ?>">
        <input type="hidden" name='start' value="<?php echo $start; ?>">
        <label for="comment"></label>
        <textarea name="comment" id="comment" cols="70" rows="2" placeholder="Write your comment here..." required></textarea>
        <input id='submit-comment' type="submit" name="submit">
    </form>
    <div id='comment-section'>
        <?php
        $sql2 = "SELECT comment,comment_id,user_id,username from comments natural join user where post_id=$post_id";
        $rez2 = mysqli_query($conn, $sql2);
        if (mysqli_num_rows($rez2) > 0) {
            while ($rowz = mysqli_fetch_assoc($rez2)) {
                $comment_id = $rowz['comment_id'];
                $user_id_of_comment = $rowz['user_id'];
                $deletevisible = '';
        ?>
                <form action="/spin/posts/postfocus.php" method="post" id="<?php echo $comment_id; ?>">
                    <input type="hidden" name='comment_id' value='<?php echo $comment_id; ?>'>
                </form>
        <?php
                if ($user_id_of_comment != $_SESSION['user_id'] && $_SESSION['username'] != $username_of_post) {
                    $deletevisible = 'deletevisible';
                }
                echo "<div class='comment-style'> <b><h6>" . $rowz['username'] . "</h6></b><p>" . $rowz['comment'] . "</p><button class='deletecmtbtn " . $deletevisible . "' onclick='deletecomment(event)' value=" . $user_id . " name=" . $comment_id . ">Delete</button>" . "</div>";
            }
        }
        ?>
    </div>
    <script>
        function deletecomment(event) {
            var comment_id = event.currentTarget.name;
            document.getElementById(comment_id).submit();
        }
    </script>
    <script>
        function modalclicked() {
            var post_id = event.currentTarget.value;
            $('.modal').on('show.bs.modal', function() {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "/spin/posts/wholiked.php", true);
                console.log(post_id);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                var params = "post_id=" + post_id;
                // console.log(params);
                xhr.onload = function() {
                    if (this.status == 200) {

                        $(".modal-body").html(this.responseText);
                    }
                }
                xhr.send(params);
            });

        }
    </script>
    <div id="myModal" class="modal fade " role="dialog">
        <!--Adding modal for who liked-->
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content ">
                <div class="modal-header">
                    Liked by
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <!-- <h4 class="modal-title">Modal Header</h4> -->
                </div>
                <div class="modal-body ">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

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
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>