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

// Delete
if(isset($_GET['delete'])) {
  $query = "delete from `$table` where id = $id";
  if(!$result = $mysqli->query($query)) die($mysqli->error);
  redirect("browse.php?table=$table");
}

// Breadcrumbs
echo breadcrumbs($table, $id);

// Query
if(!$result = $mysqli->query("select * from `$table` where id = $id"))
  die($mysqli->error);

// Record
button(ICON_BACK, "browse.php?table=$table");
button(ICON_EDIT, "edit.php?table=$table&id=$id");
button(ICON_DELETE, "?table=$table&id=$id&delete", "Delete this record?");
echo "<table>\n";
while($row = $result->fetch_assoc()) {
  foreach($row as $field=>$value) {
    if($field == "id") continue;
    $fd = getFieldData($table,$field);
    echo "<tr>";
    echo "<th>$fd[caption]</th>";
    if($fd['type'] == "checkbox") {
      $checked = ($value) ? "checked" : "";
      echo "<td><input type=\"checkbox\" disabled $checked></td>";
    }
    elseif($fd['lookup']) {
      $lookupValue = getLookupValue($fd['lookup'], $value);
      echo "<td><a href=\"view.php?table=".$fd['lookup']."&id=$value\">$lookupValue</a></td>";
    }
    else echo "<td>$value</td>";
    echo "</tr>\n";
  }
}
echo "</table>";

require("footer.php");
