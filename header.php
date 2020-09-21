<?php
   $title = basename($_SERVER['SCRIPT_NAME']) == "login.php" ? "Login" : ucwords($_SESSION['db']);
?>
<html>
<head>
<title><?php echo $title; ?> - CustomDB</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
