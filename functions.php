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

function getFieldComment($table, $field) {
  global $mysqli;
  if(!$result = $mysqli->query("show full columns from `$table` where Field = '$field'"))
    die($mysqli->error);
  $row = $result->fetch_assoc();
  return $row['Comment'];
}

function getTableComment($table) {
  global $mysqli;
  $q = "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '".DB_NAME."' and TABLE_NAME = '$table'";
  if(!$result = $mysqli->query($q)) die($mysqli->error);
  $row = $result->fetch_assoc();
  return @$row['TABLE_COMMENT'];
}
