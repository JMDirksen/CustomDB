<?php

require("init.php");

// Create table
if($_POST['tablename']) {
  $table = sanitize($_POST['tablename']);
  
  $query = "CREATE TABLE `$table` " .
    "(`id` INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (`id`))";
  if(!$mysqli->query($query)) die($mysqli->error);
  
  redirect("design.php?table=$table");
}

//ALTER TABLE `abc`
//	ADD COLUMN `test` INT(11) NOT NULL DEFAULT '0' AFTER `id`;

// Create field
if($_POST['fieldname']) {
  $table = sanitize($_POST['table']);
  $field = sanitize($_POST['fieldname']);
  
  $query = "ALTER TABLE `$table` " .
    "ADD COLUMN `$field` VARCHAR(50) NOT NULL DEFAULT ''";
  if(!$mysqli->query($query)) die($mysqli->error);
  
  redirect("design.php?table=$table");
}


require("header.php");

if(!empty($_GET['table'])) {
  $table = sanitize($_GET['table']);
  echo "Table: $table<br><br>\n";
  
  // Fields
  echo "Fields<br>\n";
  if(!$result = $mysqli->query("select * from `$table` limit 1"))
    die($mysqli->error);
  while($field = $result->fetch_field()) {
    $name = $field->name;
    $type = fieldType($field->type);
    if($name == "id") continue;
    echo "<a href=\"\">$name<a> ($type) ";
  }
  echo "<br><br>\n";

  // Create field form
  echo "<form method=\"POST\">\n";
  echo "<input type=\"hidden\" name=\"table\" value=\"$table\">";
  echo "New field <input type=\"text\" name=\"fieldname\">\n";
  echo "<input type=\"submit\" value=\"Create\">\n";
  echo "</form>\n";
}

else {
  // Tables
  echo "Tables<br>\n";
  $result = $mysqli->query("show tables");
  while($row = $result->fetch_row()) {
    $table = $row[0];
    echo "<a href=\"design.php?table=$table\">$table</a> ";
  }
  echo "<br><br>\n";

  // Create table form
  echo "<form method=\"POST\">\n";
  echo "New table <input type=\"text\" name=\"tablename\">\n";
  echo "<input type=\"submit\" value=\"Create\">\n";
  echo "</form>\n";
}

require("footer.php");
