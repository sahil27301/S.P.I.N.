<?php
    if (!isset($_POST['email'])) {
        header("Location: /spin/login/login.php");
        exit();
    }
    // print_r($_POST);
    require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/dbConnection.php';
    $stmt = $conn->prepare("select * from user where email=?");
    $stmt->bind_param("s", $email);
    $email=$_POST['email'];
    $stmt->execute();
    $result=$stmt->get_result();
    if (mysqli_num_rows($result)) {
        echo "exists";
    }
?>