<?php require('links.php'); ?>

<style>
    * {
        font-family: 'Poppins', sans-serif;
    }

    .h-font {
        font-family: 'Merienda', cursive;
    }

    body,
    .navbar {
        margin: 0;
        padding: 0;
    }

    .navbar {
        background-color: black;
        padding: 10px;
        position: relative;
        /* position: sticky;  Sticks header1 at the top */
        /* position: sticky; */
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }


    .navbar-brand {
        display: flex;
        align-items: center;
        flex-basis: 20%;
        /* Assign space for logo */
    }

    .icon-container {
        justify-content: flex-end;
        margin-right: 20px;
        /* Adjust as necessary for your layout */
        gap: 15px;
    }

    .navigation {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 20px;
        /* Adjust the space between navigation items */
        justify-content: flex-end;
        /* Aligns items to the right */
    }

    .navigation li {
        padding: 10px 20px;
    }

    .navigation a {
        color: white;
        /* Set link color to white */
        text-decoration: none;
        transition: all 0.3s ease;
    }

    /* Update link hover effect */
    .navigation a:hover {
        color: #cccccc;
        /* Lighter shade for hover */
        text-decoration: underline;
    }



    /* No changes below this point needed */
</style>

<nav class="navbar shadow-sm">
    <!-- Logo -->
    <div class="navbar-brand">
        <a href="../index.php">
            <img src="../images/logo/white.png" alt="Logo" style="height: 50px;">
        </a>
    </div>

    <!-- Navigation Links -->
    <ul class="navigation">
        <li><a href="../index.php">Home</a></li>
        <li><a href="../aboutus/aboutus.php">About</a></li>
        <li><a href="../contactus/contactus.php">Contact Us</a></li>
    </ul>
</nav>