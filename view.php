<html>
<head><title>CustomDB - View</title></head>
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

// Record
echo "<a href=\"browse.php?table=$table\">Browse</a> | <a href=\"edit.php?table=$table&id=$id\">Edit</a>\n";
echo "<table>";
while($row = $result->fetch_assoc()) {
  foreach($row as $key=>$value) {
    if($key == "id") continue;
    echo "<tr>";
    echo "<th>$key</th>";
    echo "<td>$value</td>";
    echo "</tr>\n";
  }
}
?>
</table>
</body>
</html>
