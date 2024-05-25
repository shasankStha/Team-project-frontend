<?php
session_start();
if (!isset($_SESSION["traderUser"])) {
  header("Location: ../login/login.php");
  exit;
}
header('Location:dashboard.php');
?>