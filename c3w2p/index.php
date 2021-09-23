<?php
require_once "pdo.php";
$stmt = $pdo->query("SELECT * FROM Users;");
$count = $pdo->query("SELECT COUNT(*) AS COLUMNCOUNT FROM information_schema.columns WHERE table_name = 'Users';");
$COLUMN_COUNT = $count->fetch(PDO::FETCH_ASSOC)["COLUMNCOUNT"];
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>CRUD</title>
  </head>
  <body>
    <h1>The First One</h1><hr>
    <?php
    // TABLE
    echo '<table><tr>';
    $i = 0;
    while ($i < $COLUMN_COUNT) {
        $meta = $stmt->getColumnMeta($i);
        echo '<th>' . $meta["name"] . '</th>';
        $i++;
    }
    echo '</tr>';
    $m = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo '<tr>';
      foreach ($row as $key => $value) {
        echo '<td>';
        echo $row[$key];
        echo '</td>';
      }
      echo '</tr>';
    }
    echo '</table>';
     ?>
     <hr>
     <div class="formBlock">
       <section class="newUser">
         <h3>New User</h3>
         <form class="newUserForm" method="post">
             <label for="name">Name: </label><input type="text" name="name" value="" required><br>
             <label for="email">Email Id: </label><input type="email" name="email" value="" required><br>
             <label for="pswrd">Password: </label><input type="password" name="pswrd" value="" required><br>
             <input type="submit" name="newUser" value="Add User"><br>
         </form>
       </section>
       <section class="remUser">
         <h3>Remove User</h3>
         <form class="remUserForm" method="post">
           <label for="userId">User ID: </label><input type="text" name="userId" value=""><br>
           <input type="submit" name="remUser" value="Remove User"><br>
         </form>
       </section>
     </div>
     <hr>
  </body>
</html>
