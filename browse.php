<html>
<head><title>CustomDB</title></head>
<body>
<table>
<?php
require("functions.php");

// Input
if(!empty($_GET['table'])) {
  $table = sanitize($_GET['table']);
}
else die("Missing/Wrong table variable");

// DB Connect
$mysqli = connect();

// Query
$result = $mysqli->query("select * from `$table`");
if(!$result) die("Query error");
$fields = $result->fetch_fields();

// Fields
echo "<tr>";
foreach($fields as $field) {
  echo "<th>".$field->name."</th>";
}
echo "</tr>\n";

// Rows
while($row = $result->fetch_assoc()) {
  echo "<tr>";
  foreach($row as $key=>$value) {
    echo "<td>$value</td>";
  }
  echo "</tr>\n";
}

?>
</table>
</body>
</html>
