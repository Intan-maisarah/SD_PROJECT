<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="css/design.css">

</head>

<body>
  <div class="cont">
  
    <div class="form-sign-up">


      <header></header>
      <hr>
      
      <form class="signup" action="" method="POST">

      
     


        <div class="form-sign-up">

          <?php

          session_start();

          include "connection.php";

          if (isset($_POST['register'])) {

        
            $username = $_POST['username'];
            $email = $_POST['email'];
            $pass = $_POST['password'];


           

        

           

            $check = "select * from users where email='{$email}'";

            $res = mysqli_query($conn, $check);

            $passwd = password_hash($pass, PASSWORD_DEFAULT);

          




            if (mysqli_num_rows($res) > 0) {

                echo '<script>alert("This email is used, try another email")</script>'; 
                header("Location: signup.php");
                exit();



            } else {

              if ($pass) {

                $sql = "insert into users(username,email,password) values('$username','$email','$passwd')";

                $result = mysqli_query($conn, $sql);

               


               if ($result) {

                echo '<script>alert("Youi are registered succesfully")</script>'; 
                header("Location: index.php");
                exit();


                } else {
                echo '<script>alert("Registered Error, Try another email")</script>'; 
                header("Location: signup.php");
                exit();
                }

              } else {
                echo '<script>alert("Registered Error, Try another Password")</script>'; 
                header("Location: signup.php");
                exit();
              }
            }
          } else {

            ?>

        </form>
      </div>
      <?php
          }
          ?>
  </div>

  <script>

  </script>
</body>

</html>