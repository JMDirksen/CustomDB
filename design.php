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

// Create field
if($_POST['fieldname']) {
  $table = sanitize($_POST['table']);
  $field = sanitize($_POST['fieldname']);
  $type = fieldType(sanitize($_POST['type']));
  $default = $type == "DATE" ? "1900-01-01" : "";
  
  $query = "ALTER TABLE `$table` " .
    "ADD COLUMN `$field` $type NOT NULL DEFAULT '$default'";
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
  echo "<select name=\"type\">\n";
  echo "<option value=\"text\">Text</option>\n";
  echo "<option value=\"date\">Date</option>\n";
  echo "</select>\n";
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
