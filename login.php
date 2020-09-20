<?php
  session_start();
  require("functions.php");
  require("header.php");

  if(isset($_POST['login'])) {
    if($_POST['username'] == "root") redirect();
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    
    @$mysqli = new mysqli(ini_get("mysqli.default_host"), $_SESSION['username'], $_SESSION['password']);
    if($mysqli->connect_error) {
      if(in_array($mysqli->connect_errno, array(1045,4151))) redirect('login.php');
      die($mysqli->connect_errno." ".$mysqli->connect_error);
    }
    $q = "SHOW DATABASES";
    if(!$result = $mysqli->query($q)) die($mysqli->error);

    echo "<form method=\"POST\">";
    echo "<select name=\"db\">";
    while($row = $result->fetch_assoc()) {
      $db = $row['Database'];
      $selected = ($db == $_SESSION['db']) ? " selected" : "";
      if(in_array($db, array("information_schema"))) continue;
      echo "<option value=\"$db\"$selected>".ucwords($db)."</option>";
    }
    echo "</select>";
    echo "<input type=\"submit\" name=\"dbselect\" value=\"Open\">";
    echo "</form>";
  }
  elseif(isset($_POST['dbselect'])) {
    $_SESSION['db'] = $_POST['db'];
    redirect("/");
  }
  else {
    echo "<form method=\"POST\">";
    echo "<input type=\"text\" name=\"username\">";
    echo "<input type=\"password\" name=\"password\">";
    echo "<input type=\"submit\" name=\"login\" value=\"Login\">";
    echo "</form>";
  }
    require("footer.php");
