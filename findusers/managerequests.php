<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_POST['code']) && isset($_POST['target']))) {
    header("Location: /spin/login/login.php");
    exit();
}
require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/dbConnection.php';

$user_id_1 = $_SESSION['user_id'];
$user_id_2 = $_POST['target'];
// print_r($_POST);
if ($_POST['code'] == 'add') {
    $stmt2=$conn->prepare("select privacy from user where user_id=?");
    $stmt2->bind_param("s", $user_id_2);
    $stmt2->execute();
    $result=$stmt2->get_result();
    $row=$result->fetch_assoc();
    if ($row['privacy']=='open') {
        $stmt=$conn->prepare("insert into followers(user_id_1, user_id_2) values(?,?)");
        $stmt->bind_param("ss", $user_id_1, $user_id_2);
        $stmt->execute();
        echo "open";
    }else {
        $stmt=$conn->prepare("insert into follow_requests(user_id_1, user_id_2) values(?,?)");
        $stmt->bind_param("ss", $user_id_1, $user_id_2);
        $stmt->execute();
    }
    
    // if ($stmt) {
    //     echo "Added succesfully";
    // }else {
    //     echo "fail";
    // }
}elseif ($_POST['code'] == 'remove') {
    $stmt=$conn->prepare("delete from follow_requests where user_id_1 = ? and user_id_2 = ?");
    $stmt->bind_param("ss", $user_id_1, $user_id_2);
    $stmt->execute();
    // if ($stmt) {
    //     echo "Deleted succesfully";
    // }else {
    //     echo "fail";
    // }
}elseif ($_POST['code'] == 'unfollow'){
    $stmt=$conn->prepare("delete from followers where user_id_1 = ? and user_id_2 = ?");
    $stmt->bind_param("ss", $user_id_1, $user_id_2);
    $stmt->execute();
}
?>