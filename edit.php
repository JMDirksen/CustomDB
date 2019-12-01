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
  
  redirect("view.php?table=$table&id=$id");
}

?>
<html>
<head><title>CustomDB - Edit</title></head>
<body>
<a href="javascript:history.back()">Cancel</a>
<form method="POST">
<?php

// Input
$table = isset($_GET['table']) ? sanitize($_GET['table']) : "";
$id = isset($_GET['id']) ? sanitize($_GET['id']) : "";
$new = isset($_GET['new']) ? true : false;
if(empty($table)||(empty($id)&&!$new)) die("Input error");

// Query
if($new) {
  $result = $mysqli->query("insert into `$table` () values()");
  if(!$result) die("Query error");
  $id = $mysqli->insert_id;
}
$result = $mysqli->query("select * from `$table` where id = $id");
if(!$result) die("Query error");

// Table
echo "<input type=\"hidden\" name=\"form_table\" value=\"$table\">";
echo "<input type=\"hidden\" name=\"form_id\" value=\"$id\">";
echo "<table>";
while($row = $result->fetch_assoc()) {
  foreach($row as $key=>$value) {
    if($key == "id") continue;
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
