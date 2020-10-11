<?php
    if (!(isset($_POST["username"]) && isset($_POST["password"]))) {
        header("Location: /spin/login/login.php");
        exit();
    }
    // I've used session variables to store the username on success and error message on failure
    session_start();
    echo "Checking...";
    require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/dbConnection.php';
    // The user might've entered the username or email so check both
    $stmt = $conn->prepare("select user_id,password from user where username=? or email=?");
    $stmt->bind_param("ss", $username, $username);
    $username=$_POST["username"];
    $stmt->execute();
    $result=$stmt->get_result();
    // If the username/email exists
    if(mysqli_num_rows($result))
    {
        $row=$result->fetch_assoc();
        if(password_verify($_POST["password"], $row["password"]))
        {
            $_SESSION["username"]=$username;
            $_SESSION["user_id"]=$row["user_id"];
            header("Location: /spin/home/feed.php");
            exit();
            // Redirect to the landing page
        }else {
            // Setting the error
            $_SESSION["mismatch"]="The username/email and password you entered don't match!";
        }
    }else{
        // Setting the error
        $_SESSION["non-existant"]="The username or email you entered doesn't exist!";
    }
    // Redirect
    header("Location: login.php");
    mysqli_close($conn);
?>
