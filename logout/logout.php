<?php
session_start();
$_SESSION["loggedinUser"] = false;
$_SESSION["loggedinTrader"] = false;
$_SESSION["loggedinUser"] = false;
if (isset($_SESSION["user"])) {
    session_destroy();
    echo "<script>window.location.href = '../homepage/homepage.php';</script>";
    // header("location:homepage/homepage.php");
} else {
    echo "<script>window.location.href = '../homepage/homepage.php';</script>";
    // header("location:homepage/homepage.php");
}
