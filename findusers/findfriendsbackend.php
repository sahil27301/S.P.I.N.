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
    if ($conn->connect_error)
        die("connection failed: " . $conn->connect_error);
    $searchTerm = '%'.$_GET['term'].'%';
    $searchTerm = str_replace(' ','', $searchTerm);
    // print_r($_GET);
    // $sql = "SELECT * FROM tutorials WHERE tutorial_names LIKE '%".$searchTerm."%'"; 
    $stmt = $conn->prepare("select username, firstname, lastname from user where (username like ? or concat(firstname,lastname) like ?) and username <> ?");
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $current_user);
    $current_user = $_SESSION['username'];
    $stmt->execute();
    $tutorialData = array();
    $result = $stmt->get_result(); 
    if (mysqli_num_rows($result) > 0) {
        while($row = $result->fetch_assoc()) {
            $data['label'] = $row['username']." (". $row['firstname']." ".$row['lastname'].")"; 
            $data['value'] = $row['username']; 
            array_push($tutorialData, $data);
        } 
    }
    echo json_encode($tutorialData);
?>