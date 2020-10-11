<?php
$user = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$sqlx = "SELECT profile_photo from user where user_id='$user'";
$rezx = mysqli_query($conn, $sqlx);
$row = $rezx->fetch_assoc();
?>
<div id="mySidebar" class="sidebar">
    <div class="logo ">
        <?php
        echo '
            <img src="data:image/jpeg;charset=utf8;base64,' . base64_encode($row['profile_photo']) . '" class="d-block profile_pic"  />';
        ?>
    </div>
    <a href="/spin/home/feed.php">Home</a>
    <a href="/spin/findusers/findfriends.php">Find Other Users</a>
    <a href="/spin/requests/pendingrequests.php">Pending Requests</a>
    <a href="/spin/followers/myfollowers.php">My Followers</a>
    <a href="/spin/following/following.php">Following</a>
    <a href="/spin/upload/upload.php">Upload a Post!</a>
    <a href="/spin/myposts/myposts.php">My Posts</a>
</div>
