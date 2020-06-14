<?php
require("init.php");
require("header.php");

// Input
if(!empty($_GET['table'])) {
  $table = sanitize($_GET['table']);
}
else die("Missing/Wrong table variable");

echo "<h2>".getTableData($table)['caption']."</h2>";

// Buttons
button(ICON_BACK, "/");
button(ICON_NEW, "edit.php?table=$table&new");

// Headers
if(!$result = $mysqli->query("select * from `$table`")) die($mysqli->error);
echo "<table>\n";
echo "<tr><th></th>";
$fields = $result->fetch_fields();
foreach($fields as $field) {
  $fd = getFieldData($table, $field->name);
  if($fd['hide']) continue;
  echo "<th>$fd[caption]</th>";
}
echo "</tr>\n";

// Rows
while($row = $result->fetch_assoc()) {
  $id = $row['id'];
  echo "<tr><td>";
  button(ICON_VIEW, "view.php?table=$table&id=$id");
  echo "</td>";
  foreach($row as $field=>$value) {
    $fd = getFieldData($table, $field);
    if($fd['hide']) continue;
    if($fd['type'] == "checkbox") {
      $checked = ($value) ? "checked" : "";
      echo "<td><input type=\"checkbox\" disabled $checked></td>";
    }
    else echo "<td>$value</td>";
  }
  echo "</tr>\n";
}
echo "</table>\n";

require("footer.php");
