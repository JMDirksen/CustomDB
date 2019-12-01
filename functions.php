<?php
function connect() {
  $mysqli = new mysqli("localhost", "root", "", "customdb");
  if($mysqli->connect_error) die($mysqli->connect_error);
  return $mysqli;
}

function sanitize($string) {
  return addslashes(str_replace("`", "``", $string));
}