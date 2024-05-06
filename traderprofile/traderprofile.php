<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('../inc/links.php'); ?>
    <title>Trader profile</title>
    <link rel="stylesheet" href="../css/traderprofile.css">
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

            <a href="traderprofile.php">Profile</a>
            <a href="orderhistory.php">Orders History</a>
            <a href="traderchangepassword.php">Change Password</a>
            <a href="../contactus/contactus.php">Contact Us</a>
            <a href="logout.php">Log out</a>
        </div>
        <!-- Profile Form-->
        <div class="main-content">
            <div class="profile-form">
                <h1>Profile</h1>
                <form action="profile_update.php" method="post" class="profile-info-form">
                    <div class="flex-row">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" name="last_name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" class="form-control">
                    </div>

                    <div class="flex-row">
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="date_of_birth" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-control">
                                <option value="">Select...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact-number">Contact Number</label>
                        <input type="text" id="contact-number" name="contact_number" class="form-control">
                    </div>
                    <div class="d-flex justify-content-evenly mb-2">
                        <input type="submit" value="Save Changes" class="btn btn-primary">
                        <button type="button" class="btn btn-default">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require('../inc/footer.php'); ?>

</body>

</html>