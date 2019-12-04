<html>
<head><title>CustomDB - Browse</title></head>
<body>
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
if(!$result = $mysqli->query("select * from `$table`")) die($mysqli->error);
$fields = $result->fetch_fields();

// Fields
echo "<button onClick=\"location.href='edit.php?table=$table&new'\">New</button> \n";
echo "<table>\n";
echo "<tr>";
foreach($fields as $field) {
  if($field->name == "id") continue;
  echo "<th>".$field->name."</th>";
}
echo "</tr>\n";

// Rows
while($row = $result->fetch_assoc()) {
  $id = $row['id'];
  echo "<tr>";
  foreach($row as $key=>$value) {
    if($key == "id") continue;
    echo "<td><a href=\"view.php?table=$table&id=$id\">$value</a></td>";
  }
  echo "</tr>\n";
}

?>
</table>
</body>
</html>
