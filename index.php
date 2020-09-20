<?php
require("init.php");
require("header.php");

// List tables
$q = "SELECT table_name FROM information_schema.tables WHERE table_schema = SCHEMA() ORDER BY table_name";
if(!$result = $mysqli->query($q)) die($mysqli->error);
while($row = $result->fetch_assoc()) {
    $td = getTableData($row['table_name']);
    if($td['hide']) continue;
    echo "<a href=\"browse.php?table=$row[table_name]\">$td[caption]</a><br/>";
}

echo "<br><small><a href=\"logout.php\">Logout</a></small>";
require("footer.php");
