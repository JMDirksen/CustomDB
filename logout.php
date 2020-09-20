<?php
  session_start();
  require("functions.php");
  unset($_SESSION['username']);
  unset($_SESSION['password']);
  redirect("login.php");
