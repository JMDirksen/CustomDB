<?php
require("init.php");
require("header.php");

// Input
if(!empty($_GET['table'])) {
  $table = sanitize($_GET['table']);
}
else die("Missing/Wrong table variable");

// Buttons
button("<", "/");
button("New", "edit.php?table=$table&new");

// Table
echo "<table>\n";

// Fields
if(!$result = $mysqli->query("show full columns from `$table`")) die($mysqli->error);
echo "<tr>";
while($field = $result->fetch_assoc()) {;
  $name = $field['Field'];
  $comment = $field['Comment'];
  if(substr($comment,0,1)=="_") continue;
  $display = $comment ?: $name;
  echo "<th>$display</th>";
}
echo "</tr>\n";

// Rows
if(!$result = $mysqli->query("select * from `$table`")) die($mysqli->error);
while($row = $result->fetch_assoc()) {
  $id = $row['id'];
  echo "<tr>";
  foreach($row as $key=>$value) {
    $comment = getFieldComment($table, $key);
    if(substr($comment,0,1)=="_") continue;
    echo "<td><a href=\"view.php?table=$table&id=$id\">$value</a></td>";
  }
  echo "</tr>\n";
}
echo "</table>\n";

require("footer.php");
