<?php
if ( isset($_POST['cancel']) ) {
    header("Location: index.php");
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
# Password = php123
# =>$stored_hash = hash('md5', $salt.'php123');

$failure = false;

// FORM VALIDATION
if ( isset($_POST['who']) && isset($_POST['pass']) )  {
  if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "Email and password are required";
  } else {
    $pos = strpos($_POST['who'], '@');
    if ($pos === false) {
      $failure = "Email must have an at-sign (@)";
    } else {
      $check = hash('md5', $salt.$_POST['pass']);
      if ( $check == $stored_hash ) {
        error_log("Login success ".$_POST['who']);
        header("Location: autos.php?name=".urlencode($_POST['who']));
      } else {
        error_log("Login fail ".$_POST['who']." $check");
        $failure = "Incorrect password";
      }
    }
  }
}
 ?>
 <!------------------------------------------------------------------------->
 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>Samyukt G - Login Page</title>
     <link rel="stylesheet" href="main.css">
   </head>
   <body>
     <main class="container">
      <h1>Please Log In</h1>

      <?php
      if ( $failure !== false ) {
        echo('<p id="error" style="color: red;">'.htmlentities($failure)."</p>\n");
      }
      ?>

      <form class="login" method="POST">
        <label class="input-label" for="nm">User Name</label>
        <input type="text" name="who" value="" id="nm"><br>
        <label class="input-label" for="pswrd">Password&nbsp&nbsp&nbsp</label>
        <input type="password" name="pass" id="pswrd"><br>
        <input type="submit" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
      </form>
    </main>
   </body>
 </html>
