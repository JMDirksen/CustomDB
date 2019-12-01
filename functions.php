<?php
function sanitize($string) {
  return addslashes(str_replace("`", "``", $string));
}