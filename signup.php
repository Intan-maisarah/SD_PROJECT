

          <?php

          session_start();

          include "connection.php";

          if (isset($_POST['register'])) {

        
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];


            if ($password !== $confirm_password) {
              echo '<script>alert("Passwords do not match. Please try again.");</script>';
              exit();
          }

        

           

            $check = "select * from users where email='{$email}'";

            $res = mysqli_query($conn, $check);

            /*$passwd = password_hash($pass, PASSWORD_DEFAULT); */

          




            if (mysqli_num_rows($res) > 0) {

                echo '<script>alert("This email is used, try another email")</script>'; 
                header("Location: signup.html");
                exit();



            } else {

              if ($password) {

                $sql = "insert into users(username,email,password) values('$username','$email','$password')";

                $result = mysqli_query($conn, $sql);

               


               if ($result) {

                echo '<script>alert("Youi are registered succesfully")</script>'; 
                header("Location: index.php");
                exit();


                } else {
                echo '<script>alert("Registered Error, Try another email")</script>'; 
                header("Location: signup.html");
                exit();
                }

              } else {
                echo '<script>alert("Registered Error, Try another Password")</script>'; 
                header("Location: signup.html");
                exit();
              }
            }
          } else {

            ?>

      <?php
          }
          ?>
