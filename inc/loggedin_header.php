<?php require('../inc/links.php');
?>

<style>
    * {
        font-family: 'Poppins', sans-serif;
    }

    .h-font {
        font-family: 'Merienda', cursive;
    }

    body {
        margin: 0;
        padding: 0;
    }

    .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: white;
        padding: 10px;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
    }

    .login a::after {
        content: "";
        width: 0%;
        height: 2px;
        display: block;
        background-color: orange;
        margin: auto;
        transition: 0.3s;
    }

    .login a:hover::after {
        width: 100%;
    }

    .sub-navbar {
        display: flex;
        justify-content: space-around;
        background-color: #2b2f33;
        /* Dark background for sub-navbar */
        color: white;
        /* Change text color to white */
        padding: 10px 0;
        margin-bottom: 20px;
    }

    .sub-navbar a {
        color: white;
        /* Change link color to white */
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        margin: 0 5px;
        padding: 8px 16px;
    }

    .sub-navbar a:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        background-color: #f0f0f0;
        color: black;
        /* Text color on hover */
        border-radius: 30px;
    }

    .icon-container a:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        background-color: #f0f0f0;
        color: black;
        /* Text color on hover */
        border-radius: 30px;
    }

    .search-container {
        display: flex;
        justify-content: center;
        flex-grow: 1;
        margin-right: -100px;
        margin-left: 15px;
    }

    .search-bar {
        width: 50%;
        padding: 8px;
        border: 1px solid black;
        border-radius: 20px;
    }

    .icon-container {
        display: flex;
        align-items: center;
        gap: 15px;
        /* Revert icon colors to black */
        color: black;
        /* Ensure anchor tag inherits black color for icons */
    }

    /* Specify the styles for the icons so they appear black */
    .icon-container img {
        filter: none;
        height: 24px;
        width: 24px;
    }

    .icon-container .icon {
        padding-right: 20px;
    }

    .icon-container .cart-icon {
        margin-left: 20px;
    }

    .dropdown-menu {
        display: flex;
        flex-direction: row;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-menu a {
        color: black;
        /* Text color for dropdown links */
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .user-icon:hover .dropdown-menu {
        display: block;
    }

    .user-icon .dropdown-menu {
        background-color: #2b2f33;
        /* Match the sub-navbar background */
    }

    .user-icon:hover .dropdown-menu a {
        color: white;
        /* White text for dropdown items on hover */
    }
</style>
<div>
    <nav class="navbar" style="display: flex; justify-content:space-between sticky-top;">
        <!-- Logo -->
        <div>
            <a class="navbar-brand" href="../index.php">
                <img src="../Images/logo/just_logo.png" style="height: 50px;">
            </a>
        </div>


        <div class="search_and_icons" style="display: flex; align-items:center;">
            <!-- SearchBox -->
            <div class="search-container" style="width: 900px; padding-left: 300px;">
                <input class="search-bar" type="text" placeholder="Search..." style="border-radius: 20px;">
            </div>

            <!-- Icons -->
            <div class="icon-container">
                <?php
                // Display icons if user is logged in
                echo '<div class="user-icon">
                    <img src="../images/user.png" alt="User" style="width: 24px; height: 24px; border-radius: 50%;">
                    <div class="dropdown-menu">
                        <a href="../userprofile/userprofile.php">Profile</a>
                        <a href="../logout/logout.php">Logout</a>
                    </div>
                </div>';

                echo '<div class="icons" style="display:flex; justify-content:space-evenly; padding-right: 50px;">
                    <a href="notifications.php" class="icon"><img src="../images/notifications.png" alt=""></a>
                    <a href="cart.php" class="icon"><img src="../images/cart1.png" alt="Cart"></a>
                </div>'
                ?>

            </div>
        </div>
    </nav>

    <div class="sub-navbar">
        <a href="../index.php">Home</a>
        <div class="user-icon">
            <a href="#">Shop</a>
            <div class="dropdown-menu">
                <a href="../shops/shoppage.php">Fishmonger</a>
                <a href="../shops/shoppage.php">Butcher</a>
                <a href="../shops/shoppage.php">Greengrocer</a>
                <a href="../shops/shoppage.php">Bakery</a>
                <a href="../shops/shoppage.php">Delicatessen</a>
            </div>
        </div>

        <a href="../contactus/contactus.php">Contact us</a>
        <a href="../aboutus/aboutus.php">About us</a>
    </div>
</div>