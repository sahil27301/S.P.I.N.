<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>S.P.I.N</title>
  <link href="https://fonts.googleapis.com/css2?family=Merriweather&family=Montserrat&family=Open+Sans&family=Pacifico&family=Poppins&family=Sacramento&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">

<body>

  <!-- top contains the headings -->

  <div class="top">

    <h1 class='title'>S.P.I.N</h1>
    <h3>Sardar Patel Institutional Network</h3>

  </div>

  <div>



    <form class="form" action="authenticate.php" method="post">

      <!-- Unordered list without bullets -->

      <ul style="list-style-type:none;">

        <br />

        <li>

          <!-- placeholer is for greyish text -->
          <?php
            // if an error is set, print the error and destroy the variable
            if (isset($_SESSION["non-existant"])) {
              echo $_SESSION["non-existant"]."<br>";
              unset($_SESSION["non-existant"]);
            }
          ?>
          <input class='input' type="text" name="username" placeholder="Username or Email Address" required>

        </li>

        <br />

        <li>
          <?php
            if (isset($_SESSION["mismatch"])) {
              echo $_SESSION["mismatch"]."<br>";
              unset($_SESSION["mismatch"]);
            }
          ?>
          <input class='input' type="password" name="password" placeholder="Password" required>

        </li>

        <br />

        <li>

          <input class='login' type="submit" name="" value="Log In">
          <!-- <button class='login' type="submit" name="">Log In</button> -->

        </li>

        <hr size='3' />


      </ul>

      <h3 class='newhere'>New here?</h3>

      <a class='create' href='/spin/registration/register.php'>Create a New Account</a>




    </form>




  </div>


<?php
// Making sure no sessions are set if the person is on the login page
session_destroy();
?>
</body>

</html>