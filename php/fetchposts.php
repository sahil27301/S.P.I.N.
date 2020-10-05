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
// $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
$k=$start;
if (mysqli_num_rows($result) > 0) {
    while($row=mysqli_fetch_assoc($result)){
        echo
          "<div class='posts-style'>
          <ul>
          <li>" . $row['post_id'] . "</li>
          <li>" . $row['user_id'] . "</li>
          <li>" . $row['caption'] . "</li>" .
          "<li>";
        $sql2 = "SELECT image FROM pictures WHERE post_id=".$row['post_id'];
        $result2 = mysqli_query($conn, $sql2);
        echo 
            '<div id="carousel'.$k.'" class="carousel slide" data-interval="false" data-wrap="false">
                <ol class="carousel-indicators">';
        if (mysqli_num_rows($result2)>1) {
            for ($i=0; $i < mysqli_num_rows($result2); $i++) { 
                echo "<li data-target='#carousel$k' data-slide-to='".($i+1)."'";
                if ($i==0) {
                    echo " class='active'";
                }
                echo "></li>";
            }
        }            
        echo 
            '</ol>
            <div class="carousel-inner">';
            $j=0;
            while ($row2=mysqli_fetch_assoc($result2)) {
                echo '<div class="carousel-item';
                if ($j==0)
                {
                    echo " active";
                    $j+=1;
                }
                echo '">
                <img src="data:image/jpeg;charset=utf8;base64,'.base64_encode($row2['image']).'" class="d-block" height=300 />
                </div>';
            }
            echo '</div>';
            if (mysqli_num_rows($result2)>1) {
                echo '<a class="carousel-control-prev" href="#carousel'.$k.'" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel'.$k.'" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>';
            }
                
            echo '</div>';
       
        echo
          "</li>
          </ul>
          </div>";
        $k+=1;
    }
} else{
    echo "Reached";
}