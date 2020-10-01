<?php
    if (!(isset($_POST["username"]) && isset($_POST["username"]))) {
        header("Location: login.php");
    }
    // I've used session variables to store the username on success and error message on failure
    session_start();
    echo "Checking...";
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "spin";
    $conn = mysqli_connect($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("connection failed: " . $conn->connect_error);
    }
    // The user might've entered the username or email so check both
    $stmt = $conn->prepare("select password from user where username=? or email=?");
    $stmt->bind_param("ss", $username, $username);
    $username=$_POST["username"];
    $stmt->execute();
    $result=$stmt->get_result();
    // If the username/email exists
    if(mysqli_num_rows($result))
    {
        $row=$result->fetch_assoc();
        if(password_verify($_POST["password"], $row['password']))
        {
            $_SESSION["username"]=$username;
            // Redirect to the landing page
        }else {
            // Setting the error
            $_SESSION["mismatch"]="The username/email and password you entered don't match!";
        }
    }else {
        // Setting the error
        $_SESSION["non-existant"]="The username or email you entered doesn't exist!";
    }
    // Redirect
    header("Location: login.php");
    mysqli_close($conn);
?>