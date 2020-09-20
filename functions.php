<?php
function connect() {
  @$mysqli = new mysqli(ini_get("mysqli.default_host"), $_SESSION['username'], $_SESSION['password'], $_SESSION['db']);
  if($mysqli->connect_error) {
    if(in_array($mysqli->connect_errno, array(1045,4151))) redirect('login.php');
    die($mysqli->connect_errno." ".$mysqli->connect_error);
  }
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

function getFieldData($table, $field) {
  global $mysqli;
  if(!$result = $mysqli->query("show full columns from `$table` where Field = '$field'"))
    die($mysqli->error);
  $row = $result->fetch_assoc();
  
  // Caption
  if(preg_match('/".+"/', $row['Comment']) == 1) $fd['caption'] = explode('"',$row['Comment'])[1];
  else $fd['caption'] = ucfirst($field);
  
  // Hide
  $fd['hide'] = (strpos($row['Comment'],"_") !== false) ? "1" : "0";
  
  // Type
  $type = explode("(",$row['Type'])[0];
  if(substr($type, -3) == "int") $fd['type'] = "number";
  elseif(substr($type, -4) == "char") $fd['type'] = "text";
  elseif($type == "decimal") $fd['type'] = "decimal";
  elseif($type == "date") $fd['type'] = "date";
  elseif($type == "bit") $fd['type'] = "checkbox";
  else $df['type'] = "unknown($type)";

  // Size
  if($fd['type'] == "text") $fd['size'] = rtrim(explode("(",$row['Type'])[1],")");
  
  // Decimal
  elseif($fd['type'] == "decimal") {
    list($precision,$scale) = explode(",",rtrim(explode("(",$row['Type'])[1],")"));
    $fd['min'] = rtrim("-".str_repeat("9",$precision-$scale).".".str_repeat("9",$scale),".");
    $fd['max'] = rtrim(str_repeat("9",$precision-$scale).".".str_repeat("9",$scale),".");
    if($scale == 0) $fd['step'] = "1";
    else $fd['step'] = "0.".str_repeat("0",$scale-1)."1";
  }

  // Lookup
  $fd['lookup'] = ($row['Key'] == "MUL") ? "1" : "0";

  return $fd;
}

function getTableData($table) {
  global $mysqli;
  $q = "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = SCHEMA() and TABLE_NAME = '$table'";
  if(!$result = $mysqli->query($q)) die($mysqli->error);
  $row = $result->fetch_assoc();
  
  // Caption
  if(preg_match('/".+"/', $row['TABLE_COMMENT']) == 1) $td['caption'] = explode('"',$row['TABLE_COMMENT'])[1];
  else $td['caption'] = ucfirst($table);

  // Hide
  $td['hide'] = (strpos($row['TABLE_COMMENT'],"_") !== false) ? "1" : "0";

  return $td;
}

function fkDropdown($table, $field, $selected = "") {
  global $mysqli;
  $q = "SELECT REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE " .
       "WHERE CONSTRAINT_SCHEMA = SCHEMA() and TABLE_NAME = '$table' and COLUMN_NAME = '$field'";
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
