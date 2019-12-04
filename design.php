<?php
  require("init.php");
  require("header.php");
  
  
  
  echo "<form method=\"POST\">\n";
  echo "New table <input type=\"text\" name=\"newtable\">\n";
  echo "<input type=\"submit\" value=\"Create\">\n";
  echo "</form>\n";
  
  require("footer.php");
