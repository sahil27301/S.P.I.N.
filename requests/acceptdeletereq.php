<?php
    session_start();
    if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_POST['mode']) && isset($_POST['id']))) {
    header("Location: /spin/login/login.php");
    exit();
    }
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "spin";
    $conn = mysqli_connect($host, $user, $password, $database);
    if ($conn->connect_error)
        die("connection failed: " . $conn->connect_error);
    $stmt = $conn->prepare("delete from follow_requests where user_id_1=? and user_id_2=?");
    $stmt->bind_param("ss", $user_id_1, $user_id_2);
    $user_id_1 = $_POST['id'];
    $user_id_2 = $_SESSION['user_id'];
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result)
    {
        echo "Deleted request.";
    }
    
    if ($_POST['mode'] == 'accept') {
        $stmt2 = $conn->prepare("insert into followers(user_id_1, user_id_2) values(?,?)");
        $stmt2->bind_param("ss", $user_id_1, $user_id_2);
        $stmt2->execute();
        echo "follower added.";
    }
?>