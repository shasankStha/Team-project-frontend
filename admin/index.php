<?php
session_start();

if (!isset($_SESSION["admin"]) || $_SESSION['loggedinUser'] === FALSE) {
    header("Location: ../login/login.php");
    exit;
}

header('Location:dashboard.php');
?>