<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]))) {
    header("Location: /spin/login/login.php");
    exit();
}
$host = "localhost";
$user = "root";
$password = "";
$database = "spin";
$conn = mysqli_connect($host, $user, $password, $database);
if ($conn->connect_error) {
    die("connection failed: " . $conn->connect_error);
}
if (isset($_POST['submit'])) {
    $stmt = $conn->prepare("insert into post values( ?, ?, ?, ?)");
    $stmt->bind_param("ssss", $post_id, $user_id, $upload_time, $caption);
    date_default_timezone_set("Asia/Calcutta");
    $current_time = date("Ymd") . date("His");
    $stmt2 = $conn->prepare("select count(*) from post where post_id like ?");
    $stmt2->bind_param("s", $current_time);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $row = $result->fetch_assoc();
    $post_id = $current_time . str_pad(($row['count(*)'] + 1), 4, "0", STR_PAD_LEFT);
    $user_id = $_SESSION['user_id'];
    $curr_date = substr($current_time, 0, 4) . '-' . substr($current_time, 4, 2) . '-' . substr($current_time, 6, 2);
    $curr_time = substr($current_time, 8, 2) . ':' . substr($current_time, 10, 2) . ':' . substr($current_time, 12, 2);
    $upload_time = $curr_date . ' ' . $curr_time;
    $caption = $_POST['caption'];
    $stmt2 = $conn->prepare("insert into pictures(post_id, image) values(?, ?)");
    $stmt2->bind_param("ss", $post_id, $image);
    $countfiles = count($_FILES['photos']['name']);
    $allowed_ext = array('jpg', 'png', 'jpeg', 'gif');
    $flag = true;
    for ($i = 0; $i < $countfiles; $i++) {
        $ext = strtolower(pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_ext)) {
            $flag = false;
            break;
        }
    }
    if ($flag) {
        $stmt->execute();
        for ($i = 0; $i < $countfiles; $i++) {
            $image = file_get_contents($_FILES['photos']['tmp_name'][$i]);
            $stmt2->execute();
        }
        header("Location: /spin/home/feed.php");
        exit();
    } else {
        echo "One or more unsupported image type!";
        $type_error=true;
    }
    mysqli_close($conn);
    //for viewing images see load.php
}
?>
<html>

<head>
    <meta charset="utf-8">
    <title>load posts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Montserrat&family=Open+Sans&family=Pacifico&family=Poppins&family=Sacramento&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="upload.css">
</head>

<body>

  <div class = 'top'>

    <h1 class='title'>S.P.I.N</h1>
    <h3>Sardar Patel Institutional Network</h3>
    <h1>Make a Post!</h1>

  </div>

  <div class = 'mid'>

    <form action="loadposts.php" method="post" , enctype="multipart/form-data">
        <?php
            echo "Adding posts for ".$_SESSION['username'];
        ?>


  </div>

        <hr>
        <label for="photo">Select a photo (multiple photos allowed) : </label>
        <input type="file" name="photos[]" id="photo" multiple required>
        <hr>
        <label for="caption">Enter the caption</label>
        <textarea name="caption" id="caption" cols="30" rows="5" placeholder=" Start typing here..." style="vertical-align: middle;"><?php if(isset($type_error)){echo $_POST["caption"]; unset($type_error);} ?></textarea>
        <hr>
        <button class='submit_btn' type='submit' name='submit'> Add Photos</button>
    </form>
</body>

</html>
