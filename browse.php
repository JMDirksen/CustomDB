<?php
require("init.php");
require("header.php");

// Input
if(!empty($_GET['table'])) {
  $table = sanitize($_GET['table']);
}
else die("Missing/Wrong table variable");

$display = getTableComment($table) ?: $table;
echo "<h2>$display</h2>";

// Buttons
button(ICON_BACK, "/");
button(ICON_NEW, "edit.php?table=$table&new");

// Headers
if(!$result = $mysqli->query("select * from `$table`")) die($mysqli->error);
echo "<table>\n";
echo "<tr><th></th>";
$fields = $result->fetch_fields();
foreach($fields as $field) {
  $comment = getFieldComment($table, $field->name);;
  if(substr($comment,0,1)=="_") continue;
  $display = $comment ?: $field->name;
  echo "<th>$display</th>";
}
echo "</tr>\n";

// Rows
while($row = $result->fetch_assoc()) {
  $id = $row['id'];
  echo "<tr><td>";
  button(ICON_VIEW, "view.php?table=$table&id=$id");
  echo "</td>";
  foreach($row as $field=>$value) {
    $comment = getFieldComment($table, $field);
    if(substr($comment,0,1)=="_") continue;
    echo "<td>$value</td>";
  }
  echo "</tr>\n";
}
echo "</table>\n";

require("footer.php");
