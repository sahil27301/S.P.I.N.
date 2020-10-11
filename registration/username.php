<?php
    if (!isset($_POST['username'])) {
        header("Location: /spin/login/login.php");
        exit();
    }
    // print_r($_POST);
    require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/dbConnection.php';
    $stmt = $conn->prepare("select * from user where username=?");
    $stmt->bind_param("s", $username);
    $username=$_POST['username'];
    $stmt->execute();
    $result=$stmt->get_result();
    if (mysqli_num_rows($result)) {
        echo "exists";
    }
?>