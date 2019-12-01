<html>
<head><title>CustomDB</title></head>
<body>
<?php
require("functions.php");

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
echo "<form method=\"POST\">\n";
echo "<table>\n";
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
