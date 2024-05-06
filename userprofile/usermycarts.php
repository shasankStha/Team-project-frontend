<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('../inc/links.php'); ?>
    <title>User profile</title>
    <link rel="stylesheet" href="../css/usermycarts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


</head>

</style>

</head>

<body>
    <?php
    include('../connection.php');
    session_start();

    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
    }
    ?>

    <!-- Side Bar-->
    <div class="container">
        <div class="sidebar">

            <a href="userprofile.php">Profile</a>
            <a href="userorderhistory.php">Orders History</a>
            <a href="userfavourites.php">Favourites</a>
            <a href="usermycarts.php">My carts</a>
            <a href="userchangepassword.php">Change Password</a>
            <a href="../contactus/contactus.php">Contact Us</a>
            <a href="../logout/logout.php">Log out</a>
        </div>
        <div class="main-content">
            <div class="carts-container">
                <h1>My Carts</h1>
                <div class="carts">
                    <ul class="carts-list">
                        <!-- This section should be populated with actual data using PHP or other server-side scripting -->
                        <li>Cart Item 1</li>
                        <li>Cart Item 2</li>
                        <li>Cart Item 3</li>
                        <li>Cart Item 4</li>
                        <!-- Repeat list items as necessary -->
                    </ul>
                </div>
            </div>
        </div>



    </div>

    <?php require('../inc/footer.php'); ?>

</body>

</html>