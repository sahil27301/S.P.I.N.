<?php
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
    $user_id = $_POST['user_id'];
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
    } else {
        echo "One or more unsupported image type!";
    }
    //for viewing images see load.php
}
?>
<html>

<head>
    <meta charset="utf-8">
    <title>load posts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
    <h1>Trial posts form</h1>
    <form action="loadposts.php" method="post" , enctype="multipart/form-data">
        <label for="user_id">Select the user id: </label>
        <select name="user_id" id="user_id">
            <?php
            $stmt = $conn->prepare("select user_id from user");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "
                    <option value=" . $row['user_id'] . ">" . $row['user_id'] . "</option>
                ";
            }
            mysqli_stmt_close($stmt);
            ?>
        </select>
        <hr>
        <label for="photo">Select a photo (multiple photos allowed): </label>
        <input type="file" name="photos[]" id="photo" multiple>
        <hr>
        <label for="caption">Enter the caption</label>
        <textarea name="caption" id="caption" cols="30" rows="5" placeholder="Start typing here..." style="vertical-align: middle;"></textarea>
        <hr>
        <button type='submit' name='submit'> add photos</button>
    </form>
</body>

</html>