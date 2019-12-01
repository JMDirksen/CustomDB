<?php
require("functions.php");

// DB Connect
$mysqli = connect();

// Save
if(isset($_POST['form_submit'])) {
  echo "Saving...";
  
  $setfields = [];
  foreach($_POST as $key=>$value) {
    if($key == "form_table") $table = $value;
    if($key == "form_id") $id = $value;
    if(substr($key,0,5) != "form_") $setfields[] = "$key = '$value'";
  }
  
  // Query
  $query = "update `$table` set ".join(", ",$setfields)." where id = $id";
  $result = $mysqli->query($query);
  if(!$result) die("Query error");
  
  redirect("view.php?".$_SERVER['QUERY_STRING']);
}

?>
<html>
<head><title>CustomDB</title></head>
<body>
<a href="javascript:history.back()">Back</a>
<form method="POST">
<?php

// Input
if(!empty($_GET['table'])) {
  $table = sanitize($_GET['table']);
}
else die("Missing/Wrong table variable");
if(!empty($_GET['id'])) {
  $id = sanitize($_GET['id']);
}
else die("Missing/Wrong id variable");

// Query
$result = $mysqli->query("select * from `$table` where id = $id");
if(!$result) die("Query error");

// Table
echo "<input type=\"hidden\" name=\"form_table\" value=\"$table\">";
echo "<input type=\"hidden\" name=\"form_id\" value=\"$id\">";
echo "<table>";
while($row = $result->fetch_assoc()) {
  foreach($row as $key=>$value) {
    echo "<tr>";
    echo "<td>$key</td>";
    echo "<td><input type=\"text\" name=\"$key\" value=\"$value\"></td>";
    echo "</tr>\n";
  }
}
?>
<tr><td></td><td><input type="submit" name="form_submit" value="Save"</td></tr>
</table>
</form>
</body>
</html>
