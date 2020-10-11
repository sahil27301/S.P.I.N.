<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "spin";
$conn = mysqli_connect($host, $user, $password, $database);
if ($conn->connect_error)
    die("connection failed: " . $conn->connect_error);
if (isset($_POST['start']) && isset($_POST['limit'])) {
    $start = mysqli_real_escape_string($conn, $_POST['start']);
    $limit = mysqli_real_escape_string($conn, $_POST['limit']);
}
$start = mysqli_real_escape_string($conn, $_POST['start']);
$limit = mysqli_real_escape_string($conn, $_POST['limit']);
$user_id = $_SESSION['user_id'];
$sql = "SELECT post_id,username,caption from post natural join user where user_id='$user_id' LIMIT $limit OFFSET $start"; //We need to change this query since friends can see their friends posts only
$result = mysqli_query($conn, $sql);

$k = $start;

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $username = $row['username'];
        $sqlx = "SELECT profile_photo from user where username='$username'";
        $rezy = mysqli_query($conn, $sqlx);
        $rowxx = $rezy->fetch_assoc();
        echo
            "<div class='posts-style'>
            <div>
            <img src='data:image/jpeg;charset=utf8;base64," . base64_encode($rowxx['profile_photo']) . "' class='d-block profile_pic_in_posts' height=300 />
            <h5 class='post-username'>" . $row['username'] . "</h5>
            <p class='post-caption'>" . $row['caption'] . "</p>
            </div>
            <ul>
          " .
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
                <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row2['image']) . '" class="d-block" height=300 style="margin:auto"/>
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

        echo '</div>';

        echo
            "</li>
          </ul>
          <div><button class='likebtn " . $likestatus . "'id=" . $like_id . " name=" . $like_id . " value= '0' onclick='likeme(event)'><i class='fas fa-thumbs-up fa-2x'></i> <h5>" . $likecount['likeCount'] . "</h5></button><button onclick='focuss(event)' name=" . $like_id . " class='comment btn btn-success'>View comments</button></div>
          </div>";
        $k += 1;
    }
} else {
    echo "Reached";
}
