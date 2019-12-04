<?php
require("init.php");
require("header.php");

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
if(!$result = $mysqli->query("select * from `$table` where id = $id"))
  die($mysqli->error);

// Record
button("<", "browse.php?table=$table");
button("Edit", "edit.php?table=$table&id=$id");
echo "<table>\n";
while($row = $result->fetch_assoc()) {
  foreach($row as $key=>$value) {
    if($key == "id") continue;
    echo "<tr>";
    echo "<th>$key</th>";
    echo "<td>$value</td>";
    echo "</tr>\n";
  }
}
echo "</table>";

require("footer.php");
