<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/design.css">

</head>

<body>

  <div class="cont">
    <div class="form-sign in">
    <form class="signin" action="" method="POST">


    

      <?php
      include "connection.php"; 

      if (isset($_POST['signin'])) {

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $pass = $_POST['password'];

        $sql = "select * from users where username='$username'";

        $res = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) > 0) {

          $row = mysqli_fetch_assoc($res);

          $password = $row['password'];

          $decrypt = password_verify($pass, $password);



          if ($decrypt) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $row['password'];
            header("Location: index.php");
            exit();
    


          } else {

            echo '<script>alert("Wrong Password")</script>'; 
            header("Location: signin.html");
            exit();

          }

        } else {

            echo '<script>alert("Wrong Email or Password")</script>'; 
            header("Location: signin.html");
            exit();

        }


      } else 
  
      {


        ?>
    </form>
      <?php
      }
      ?>
  </div>
  <script>

  </script>
</body>

</html>