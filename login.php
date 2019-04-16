<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="css/custom.css">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

        <!-- jQuery Modal -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

        <title>Login</title>
    </head>
    <body>
        <div id="header">
            <img src="http://a.espncdn.com/i/espn/teamlogos/lrg/trans/espn_dotcom_black.gif" />
            <h1>Sports News</h1>
        </div>

      <h1>Login Screen</h1>
      <div>

      <?php
        session_start();

        if($_SESSION["username"] != "" && $_SESSION["password"] != "") {
          header("Location:index.php");
        }

        if (isset($_POST['signup']) && !empty($_POST['signUpName'])
           && !empty($_POST['signUpPass'])) {

           $form = array($_POST['signUpName']=>$_POST['signUpPass']);

           $jsonData = file_get_contents('credentials.json');
           $jsonArray = json_decode($jsonData, true);

           $userExists = false;
           foreach ($jsonArray as $index){
             if(array_key_exists($_POST['signUpName'],$index)){
               $userExists = true;
             }
           }
           if($userExists) {
            echo "<p style='color:red'>Invalid username/password.</p>";
           } else {
             array_push($jsonArray,$form);

             $jsonData = json_encode($jsonArray, true);

             file_put_contents('credentials.json',$jsonData);
             echo "<p style='color:green'>Account created.</p>";
           }
        }
      ?>

      </div>

      <div id="ex1" class="modal">
        <form class = "signin" role = "form"
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
            ?>" method = "post">
            <input type = "text" placeholder="Username" name = "signUpName" class="form-control" required autofocus>
            <br/>
            <input type = "password"  placeholder="Password" name = "signUpPass" class="form-control"  required>
            <br/>
            <button type = "submit" name = "signup" class="btn">Sign Up</button>
         </form>
         <br/>

         <?php
            if (isset($_POST['login']) && !empty($_POST['username'])
              && !empty($_POST['password'])) {
               $form = array($_POST['username']=>$_POST['password']);

               $jsonData = file_get_contents('credentials.json');
               $jsonArray = json_decode($jsonData, true);

               $passwordCorrect = false;
               foreach ($jsonArray as $index){
                 if(array_key_exists($_POST['username'],$index)){
                   if($index[$_POST['username']] == $_POST['password']){
                     $passwordCorrect = true;
                   }
                 }
               }
               if($passwordCorrect) {
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['password'] = $_POST['password'];
                header("Location:index.php");
               } else {
                 echo "<p style='color:red'>Incorrect username/password.</p>";
               }
            }
         ?>
      </div>

      <p><a href="#ex1" rel="modal:open">Sign Up</a></p>

      <div>
        <form class = "signin" role = "form"
           action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
           ?>" method = "post">
           <input type = "text" placeholder="Username" name = "username" class="form-control"  required autofocus>
           <br/>
           <input type = "password"  placeholder="Password" name = "password" class="form-control" required>
           <br/><br/>
           <button class="btn" type="submit" name="login">Log In</button>
        </form>
        <br/><br/>

      </div>
   </body>
</html>
