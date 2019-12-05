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

function button($text, $href = "") {
  echo "<button onClick=\"location.href='$href'\">$text</button>\n";
}

function fieldType($type) {
  if($type == "253") return "text";
  if($type == "text") return "VARCHAR(50)";
  if($type == "10") return "date";
  if($type == "date") return "DATE";
}
