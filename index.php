<?php
require("init.php");
require("header.php");

// List tables
$q = "SELECT TABLE_NAME, TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE table_schema='".DB_NAME."'";
if(!$result = $mysqli->query($q)) die($mysqli->error);
while($row = $result->fetch_assoc()) {
    $table = $row['TABLE_NAME'];
    $td = getTableData($table);
    if($td['hide']) continue;
    echo "<a href=\"browse.php?table=$table\">$td[caption]</a><br/>";
}

require("footer.php");
