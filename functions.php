<?php
function connect() {
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if($mysqli->connect_error) die($mysqli->connect_error);
  return $mysqli;
}

function sanitize($string) {
  return addslashes(str_replace("`", "``", $string));
}

function redirect($url = "") {
  if(empty($url)) $url = $_SERVER['REQUEST_URI'];
  header("Location: $url", true, 303);
  die();
}

function button($text, $href = "", $confirm = "") {
  if($confirm) echo "<button onClick=\"if(confirm('$confirm')) location.href='$href'\">$text</button>\n";
  else echo "<button onClick=\"location.href='$href'\">$text</button>\n";
}

function getFieldType($table, $field) {
  global $mysqli;
  if(!$result = $mysqli->query("show columns from `$table` where Field = '$field'"))
    die($mysqli->error);
  $row = $result->fetch_assoc();
  $type = explode("(",$row['Type'])[0];
  if(substr($type, -3) == "int") return "number";
  if(substr($type, -4) == "char") return "text";
  if($type == "date") return "date";
  if($type == "bit") return "checkbox";
  return "unknown($type)";
}

function getFieldSize($table, $field) {
  global $mysqli;
  if(!$result = $mysqli->query("show columns from `$table` where Field = '$field'"))
    die($mysqli->error);
  $row = $result->fetch_assoc();
  return trim(explode("(",$row['Type'])[1],"()");
}

function getFieldComment($table, $field) {
  global $mysqli;
  if(!$result = $mysqli->query("show full columns from `$table` where Field = '$field'"))
    die($mysqli->error);
  $row = $result->fetch_assoc();
  return $row['Comment'];
}

function getTableComment($table) {
  global $mysqli;
  $q = "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = SCHEMA() and TABLE_NAME = '$table'";
  if(!$result = $mysqli->query($q)) die($mysqli->error);
  $row = $result->fetch_assoc();
  return @$row['TABLE_COMMENT'];
}

function isFK($table, $field) {
  global $mysqli;
  if(!$result = $mysqli->query("show columns from `$table` where Field = '$field'"))
    die($mysqli->error);
  $row = $result->fetch_assoc();
  return $row['Key'] == "MUL";
}

function fkDropdown($table, $field, $selected = "") {
  global $mysqli;
  $q = "SELECT REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_SCHEMA = SCHEMA() and TABLE_NAME = '$table' and COLUMN_NAME = '$field'";
  if(!$result = $mysqli->query($q)) die($mysqli->error);
  $row = $result->fetch_assoc();
  $r_table = $row['REFERENCED_TABLE_NAME'];
  $r_field = $row['REFERENCED_COLUMN_NAME'];
  
  $dropdown = "<select name=\"$field\">";
  if(!$result = $mysqli->query("select $r_field from $r_table")) die($mysqli->error);
  while($row = $result->fetch_assoc()) {
    $value = $row[$r_field];
    $sel = ($value == $selected) ? " selected" : "";
    $dropdown .= "<option value=\"$value\"$sel>$value</option>";
  }
  $dropdown .= "</select>\n";
  return $dropdown;
}