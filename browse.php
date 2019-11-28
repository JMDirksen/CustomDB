<html>
<head><title>CustomDB</title></head>
<body>
<table>
<?php

$table = $_GET['table'];
$db = new mysqli("localhost", "root", "", "customdb");
if($db->connect_error) die($db->connect_error);
$result = $db->query("select * from $table");
if(!$result) die("Query error");
$fields = $result->fetch_fields();

// Fields
echo "<tr>";
foreach($fields as $field) {
    echo "<th>".$field->name."</th>";
}
echo "</tr>".PHP_EOL;

// Rows
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    foreach($row as $key=>$value) {
        echo "<td>$value</td>";
    }
    echo "</tr>".PHP_EOL;
}

?>
</table>
</body>
</html>
