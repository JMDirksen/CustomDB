<?php
require("init.php");

// Save
if(isset($_POST['form_submit'])) {
  $setfields = [];
  foreach($_POST as $key=>$value) {
    $key = sanitize($key);
    $value = sanitize($value);
    if($key == "form_table") $table = $value;
    if($key == "form_id") $id = $value;
    if(substr($key,0,5) != "form_") {
      $prefix = (getFieldType($table, $key) == "checkbox") ? "b" : "";
      $setfields[] = "`$key` = $prefix'$value'";
    }
  }
  
  $q = "update `$table` set ".join(", ",$setfields)." where id = $id";
  if(!$result = $mysqli->query($q)) die($mysqli->error);
  
  redirect("view.php?table=$table&id=$id");
}

require("header.php");

// Input
$table = isset($_GET['table']) ? sanitize($_GET['table']) : "";
$id = isset($_GET['id']) ? sanitize($_GET['id']) : "";
$new = isset($_GET['new']) ? true : false;
if(empty($table)||(empty($id)&&!$new)) die("Input error");

// Query
if($new) {
  if(!$result = $mysqli->query("insert into `$table` () values()"))
    die($mysqli->error);
  $id = $mysqli->insert_id;
}
if(!$result = $mysqli->query("select * from `$table` where id = $id"))
  die($mysqli->error);

// Table
if($new) button(ICON_BACK, "view.php?table=$table&id=$id&delete");
else {
  button(ICON_BACK, "view.php?table=$table&id=$id");
}
echo "<form method=\"POST\">\n";
echo "<input type=\"hidden\" name=\"form_table\" value=\"$table\">\n";
echo "<input type=\"hidden\" name=\"form_id\" value=\"$id\">\n";
echo "<table>\n";
$row = $result->fetch_assoc();
foreach($row as $field=>$value) {
  if($field == "id") continue;
  $type = getFieldType($table, $field);
  $display = getFieldComment($table, $field) ?: $field;
  echo "<tr>";
  echo "<th>$display</th>";
  
  // Foreign key
  if(isFK($table, $field)) {
    echo "<td>".fkDropdown($table, $field, $value)."</td>";
  }

  // Checkbox
  else if($type == "checkbox") {
    $checked = ($value == "1") ? " checked": "";
    echo "<td><input type=\"hidden\" name=\"$field\" value=0>";
    echo "<input type=\"$type\" name=\"$field\" value=1$checked></td>";
  }

  // Number
  else if($type == "number") {
    echo "<td><input type=\"$type\" name=\"$field\" value=\"$value\" min=-2147483648 max=2147483647></td>";
  }

  // Text
  else if($type == "text") {
    $maxlength = getFieldSize($table,$field);
    echo "<td><input type=\"$type\" name=\"$field\" value=\"$value\" maxlength=$maxlength></td>";
  }

  // Date
  else if($type == "date") {
    echo "<td><input type=\"$type\" name=\"$field\" value=\"$value\"></td>";
  }

  echo "</tr>\n";
}
?>
<tr>
  <td></td>
  <td>
    <input type="submit" name="form_submit" value="<?php echo ICON_SAVE ?>">
  </td>
</tr>
</table>
</form>
<?php
require("footer.php");
