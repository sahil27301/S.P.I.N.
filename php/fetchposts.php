<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_POST['start']))) {
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


$start = mysqli_real_escape_string($conn, $_POST['start']);
$limit = mysqli_real_escape_string($conn, $_POST['limit']);
$sql = "SELECT * from post LIMIT $limit OFFSET $start"; //We need to change this query since friends can see their friends posts only
$result = mysqli_query($conn, $sql);
$user_id = $_SESSION['user_id'];

// $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
$k = $start;

if (mysqli_num_rows($result) > 0) {
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
        $query = "SELECT * from likes where user_id='$user_id' and post_id='$like_id'";
        $queryresult = mysqli_query($conn, $query);
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

        echo '</div>';

        echo
            "</li>
          </ul>
          <div><button class='likebtn " . $likestatus . "'id=" . $like_id . " name=" . $like_id . " value= '0' onclick='likeme(event)'><i class='fas fa-thumbs-up fa-2x'></i></button><button onclick='focuss(event)' name=" . $like_id . " value=" . $start . " class='comment'>Add a comment</button></div>
          </div>";
        $k += 1;
    }
} else {
    echo "Reached";
}
