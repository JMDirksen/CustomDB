<?php
require("functions.php");

// Save
if(isset($_POST['submit'])) {
  echo "Saving...";
  redirect("view.php?".$_SERVER['QUERY_STRING']);
}

?>
<html>
<head><title>CustomDB</title></head>
<body>
<a href="javascript:history.back()">Back</a>
<form method="POST">
<table>
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

// DB Connect
$mysqli = connect();

// Query
$result = $mysqli->query("select * from `$table` where id = $id");
if(!$result) die("Query error");

// Form
while($row = $result->fetch_assoc()) {
  foreach($row as $key=>$value) {
    echo "<tr>";
    echo "<td>$key</td>";
    echo "<td><input type=\"text\" value=\"$value\"></td>";
    echo "</tr>\n";
  }
}
?>
<tr><td></td><td><input type="submit" name="submit" value="Save"</td></tr>
</table>
</form>
</body>
</html>
