<?php
$errors=array('username' =>'','email'=>'');
if (isset($_POST['insert'])) {

    require $_SERVER['DOCUMENT_ROOT'].'/spin/partials/dbConnection.php';
    // Check if the username is unique
    $username_entered= mysqli_real_escape_string($conn,$_POST['username']);
    $sql= "SELECT username from user where username='$username_entered'";
    $username=mysqli_query($conn,$sql);
    $username_from_db = mysqli_num_rows($username);
    if($username_from_db)
    {
    $errors['username']="Username already exists.Please select a different one";
    }

    // Check if the username is unique
    $email_entered= mysqli_real_escape_string($conn,$_POST['email']);
    $sql= "SELECT email from user where email='$email_entered'";
    $email=mysqli_query($conn,$sql);
    $email_from_db = mysqli_num_rows($email);
    if($email_from_db)
    {
    $errors['email']="This email has already been registered with S.P.I.N";
    }

    if(!array_filter($errors))
    {
    $stmt = $conn->prepare("insert into user (firstname, lastname, user_id, bio, privacy, dob, username, password, email, profile_photo) values
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssssssss", $firstname, $lastname, $user_id, $bio, $privacy, $dob, $username, $password, $email, $profile_photo);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    // Generate a user_id automatically
    $stmt2 = $conn->prepare("select count(*) from user where user_id like ?");
    $stmt2->bind_param("s", $current_date);
    $actual_date = date("Ymd");
    $current_date = $actual_date . "%";
    // $query = "select count(*) from user where user_id like '" . $current_date . "%'";
    // $result = mysqli_query($conn, $query);
    // $row = mysqli_fetch_array($result);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $row = $result->fetch_assoc();
    $user_id = $actual_date . str_pad(($row['count(*)'] + 1), 4, "0", STR_PAD_LEFT);
    // echo "generated user_id is ".$user_id."<br>";
    //
    $bio = $_POST['bio'];
    $privacy = $_POST['privacy'];
    $dob = $_POST['dob'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $profile_photo = file_get_contents($_FILES["profile_photo"]["tmp_name"]);
    $stmt->execute();
    echo "Added details succesfully!";
    // to print all the images in the database
    // $query="select * from user";
    // $result=mysqli_query($conn, $query);
    // while ($row= mysqli_fetch_array($result) ) {
    //     echo '<img src="data:image/jpeg;charset=utf8;base64,'.base64_encode($row['profile_photo']).'" />';
    //     echo '<br>';
    // }
    mysqli_stmt_close($stmt2);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: /spin/login/login.php");
    exit();
  }
}
?>
<html>

<head>
    <meta charset="utf-8">
    <title>load data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Montserrat&family=Open+Sans&family=Pacifico&family=Poppins&family=Sacramento&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="register.css">
</head>


  <div class = 'top'>

    <h1 class='title'>S.P.I.N</h1>
    <h3>Sardar Patel Institutional Network</h3>
    <h1 class = 'secondTitle' >Sign Up</h1>

  </div>

  <div class="mid">

    <!--I made labels simpler and concise-->

      <form action="register.php" method="post" , enctype="multipart/form-data" autocomplete="off">
          <label for="firstname">First name</label>
          <input type="text" id="firstname" name="firstname" required
            <?php if (isset($_POST["firstname"]))
            {
                echo "value=".$_POST["firstname"];
            }?>
          >
          <hr>
          <label for="lastname">Last name</label>
          <input type="text" id="lastname" name="lastname" required
            <?php if (isset($_POST["lastname"]))
            {
                echo "value=".$_POST["lastname"];
            }?>
          >
          <hr>
          <label for="bio">Bio</label>
          <textarea name="bio" id="bio" cols="30" rows="5" placeholder=" Start typing here..." style="vertical-align: middle;"><?php if (isset($_POST["bio"])){echo $_POST["bio"];}?></textarea>
          <hr>
          <label>Privacy type</label>
          <br>
          <label for="private">Private</label>
          <input type="radio" name="privacy" id="private" value="private" checked>
          <br>
          <label for="open">Open</label>
          <input type="radio" name="privacy" id="open" value="open"
          <?php
            if (isset($_POST["privacy"]) && $_POST["privacy"]=="open")
            {
                echo "checked";
            }
          ?>
          >
          <hr>
          <label for="dob">Date of birth</label>
          <input type="date" id="dob" name="dob" required
            <?php if (isset($_POST["dob"]))
            {
                echo "value=".$_POST["dob"];
            }?>
          >
          <hr>
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required
            <?php if (isset($_POST["username"]))
            {
                echo "value=".$_POST["username"];
            }?>
          >
          <div id='usernameError' class='btn-danger' style='width:30%; margin:10px auto;border-radius:10px ;padding:10px'>Username already exists!</div>
       	  <div class=""><?php echo $errors['username'];?></div>   <!--Outputs error-->
          <hr>
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
          <hr>
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required
            <?php if (isset($_POST["email"]))
            {
                echo "value=".$_POST["email"];
            }?>
          >
          <div id='emailError' class='btn-danger' style='width:30%; margin:10px auto;border-radius:10px ;padding:10px'>Email already exists!</div>
          <div class=""><?php echo $errors['email'];?></div>   <!--Outputs error-->
          <hr>
          <label for="profile_photo">Select an image file</label>
          <input type="file" name="profile_photo" id="profile_photo">
          <hr class='end-hr'>
          <input class='submit_btn' type="submit" value="Insert Details" name="insert" id="insert">

  </div>


    </form>
</body>
<script>
    $("#usernameError").hide();
    $("#emailError").hide();
    $(document).ready(function() {
        $("#insert").click(function() {
            var image_name = $("#profile_photo").val();
            if (image_name == '') {
                alert('Please select an image!');
                return false;
            }
            var extension = $("#profile_photo").val().split('.').pop().toLowerCase();
            if (jQuery.inArray(extension, ["gif", "png", "jpg", "jpeg"]) == -1) {
                alert('Picture format not supported!');
                return false;
            }
        });
    });
    $("#username").on("paste input", function(){
      // console.log($(this).val());
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "username.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      params='username='+$(this).val();
      xhr.onload = function (event){
        if (this.status == 200){
          // console.log(this.responseText);
          if (this.responseText == 'exists')
          {
            $("#insert").prop('disabled',true);
            $('#usernameError').show();
          }else
          {
            $("#insert").prop('disabled',false);
            $('#usernameError').hide();
          }
        }
      }
      xhr.send(params);
    });
    $("#email").on("paste input", function(){
      // console.log($(this).val());
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "email.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      params='email='+$(this).val();
      xhr.onload = function (event){
        if (this.status == 200){
          // console.log(this.responseText);
          if (this.responseText == 'exists')
          {
            $("#insert").prop('disabled',true);
            $('#emailError').show();
          }else
          {
            $("#insert").prop('disabled',false);
            $('#emailError').hide();
          }
        }
      }
      xhr.send(params);
    });
</script>

</html>
