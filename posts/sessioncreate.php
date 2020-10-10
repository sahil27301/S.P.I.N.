<?php
session_start();
if (!(isset($_SESSION["username"]) && isset($_SESSION["user_id"]))) {
    header("Location: /spin/login/login.php");
    exit();
}
if (isset($_POST['post_id'])) {
    $_SESSION['post_id'] = $_POST['post_id'];
    $_SESSION['start'] = $_POST['start'];
    $post_id = $_SESSION['post_id'];
    $start = $_SESSION['start'];
} else {
    echo "error";
}
