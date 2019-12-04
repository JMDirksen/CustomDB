<?php
require("functions.php");

// DB Connect
$mysqli = connect();

// Save
if(isset($_POST['form_submit'])) {
  $setfields = [];
  foreach($_POST as $key=>$value) {
    $key = sanitize($key);
    $value = sanitize($value);
    if($key == "form_table") $table = $value;
    if($key == "form_id") $id = $value;
    if(substr($key,0,5) != "form_") {
      $setfields[] = "`$key` = '$value'";
    }
  }
  
  $query = "update `$table` set ".join(", ",$setfields)." where id = $id";
  if(!$result = $mysqli->query($query)) die($mysqli->error);
  
  redirect("view.php?table=$table&id=$id");
}

// Delete
if(isset($_GET['delete']) && !empty($_GET['table']) && !empty($_GET['id'])) {
  $table = sanitize($_GET['table']);
  $id = sanitize($_GET['id']);
  
  $query = "delete from `$table` where id = $id";
  if(!$result = $mysqli->query($query)) die($mysqli->error);
  
  redirect("browse.php?table=$table");
  
}

?>
<html>
<head><title>CustomDB - Edit</title></head>
<body>
<?php

// Input
$table = isset($_GET['table']) ? sanitize($_GET['table']) : "";
$id = isset($_GET['id']) ? sanitize($_GET['id']) : "";
$new = isset($_GET['new']) ? true : false;
if(empty($table)||(empty($id)&&!$new)) die("Input error");

// Query
if($new) {
  if(!$result = $mysqli->query("insert into `$table` () values()"))
    die($mysqli->error);
  $id = $mysqli->insert_id;
}
if(!$result = $mysqli->query("select * from `$table` where id = $id"))
  die($mysqli->error);

// Table
if($new) echo "<button onClick=\"location.href='edit.php?table=$table&id=$id&delete'\"><</button> \n";
else {
  echo "<button onClick=\"location.href='view.php?table=$table&id=$id'\"><</button> \n";
  echo "<button onClick=\"location.href='edit.php?table=$table&id=$id&delete'\">Delete</button> \n";
}
echo "<form method=\"POST\">\n";
echo "<input type=\"hidden\" name=\"form_table\" value=\"$table\">\n";
echo "<input type=\"hidden\" name=\"form_id\" value=\"$id\">\n";
echo "<table>\n";
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
