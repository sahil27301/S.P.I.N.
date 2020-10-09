<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]))) {
    header("Location: login.php");
    exit();
}
$host = "localhost";
$user = "root";
$password = "";
$database = "spin";
$conn = mysqli_connect($host, $user, $password, $database);
if ($conn->connect_error)
    die("connection failed: " . $conn->connect_error);
$comment_box = '';
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
    <?php
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
        echo $comment_id;
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $start = mysqli_real_escape_string($conn, $_POST['start']);
        $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
        $sql = "INSERT into comments VALUES('$post_id','$comment','$comment_id')";
        $rez = mysqli_query($conn, $sql);
    } else {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $url = "https://";
        else
            $url = "http://";
        // Append the host(domain name, ip) to the URL.   
        $url .= $_SERVER['HTTP_HOST'];

        // Append the requested resource location to the URL   
        $url .= $_SERVER['REQUEST_URI'];

        // echo $url;
        $parts = parse_url($url);
        parse_str($parts['query'], $query);
        $post_id = $query['post_id'];
        $start = $query['start'];
    }

    $sql = "SELECT user_id,caption,post_id from post where post_id='$post_id'";
    $result = mysqli_query($conn, $sql);
    $sql2 = "SELECT image from pictures where post_id='$post_id";
    $res2 = mysqli_query($conn, $sql2);
    $user_id = $_SESSION['user_id'];
    $k = $start;
    while ($row = mysqli_fetch_assoc($result)) {
        echo
            "<div class='posts-style'>
          <ul>
          <li>" . $row['post_id'] . "</li>
          <li>" . $row['user_id'] . "</li>
          <li>" . $row['caption'] . "</li>" .
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
        echo "Likecount:" . $likecount['likeCount'];
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
                <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row2['image']) . '" class="d-block" height=300 />
                </div>';
        }
        echo '</div>';
        if (mysqli_num_rows($result2) > 1) {
            echo '<a class="carousel-control-prev" href="#carousel' . $k . '" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel' . $k . '" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>';
        }

        echo '</>';

        echo
            "</li>
          </ul>
          <div><button class='likebtn " . $likestatus . "'id=" . $like_id . " name=" . $like_id . " value= '0' onclick='likeme(event)'><i class='fas fa-thumbs-up fa-2x'></i></button></div>
          </div>";
        $k += 1;
    }
    ?>
    <script>

    </script>
    <form action="/spin/php/postfocus.php" method="POST">
        <input type="hidden" name='post_id' value="<?php echo $post_id; ?>">
        <input type="hidden" name='start' value="<?php echo $start; ?>">
        <label for="comment"></label>
        <textarea name="comment" id="comment" cols="70" rows="2"></textarea>
        <input type="submit" name="submit">
    </form>
    <div id='comment-section'>
        <?php
        $sql2 = "SELECT comment,comment_id from comments where post_id=$post_id";
        $rez2 = mysqli_query($conn, $sql2);
        if (mysqli_num_rows($rez2) > 0) {
            while ($rowz = mysqli_fetch_assoc($rez2)) {
                // echo "<div class='comment-style'>" . $rowz['comment'] . "</div>";
                echo "<div class='comment-style'>" . $user_id . "<br>" . $rowz['comment'] . "<button class='deletecmtbtn' value=" . $user_id . "name=" . $rowz['comment_id'] . ">Delete</button>" . "</div>";
            }
        }
        ?>
    </div>
</body>

</html>