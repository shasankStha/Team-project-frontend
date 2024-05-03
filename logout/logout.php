<?php
session_start();
if (isset($_SESSION["user"])) {
    session_destroy();
    echo "<script>window.location.href = '../homepage/homepage.php';</script>";
    // header("location:homepage/homepage.php");
} else {
    echo "<script>window.location.href = '../homepage/homepage.php';</script>";
    // header("location:homepage/homepage.php");
}
