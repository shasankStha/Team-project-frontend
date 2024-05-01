<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('../inc/links.php'); ?>
    <title>Trader change password - CleckShopHub</title>
    <link rel="stylesheet" href="../css/traderchangepassword.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
 
</head>

</style>

</head>

<body>
    <?php require('../inc/header.php'); ?>

    <!-- Side Bar-->
    <div class="container">
        <div class="sidebar">
            <a href="traderprofile.php">Profile</a>
            <a href="orderhistory.php">Orders History</a>
            <a href="traderchangepassword.php">Change Password</a>
            <a href="../contactus/contactus.php">Contact Us</a>
            <a href="logout.php">Log out</a>
        </div>


        <div class="main-content">
            <div class="profile-form">
                <h1>Change Password</h1>
                <form action="password_update.php" method="post" class="password-change-form">
                    <div class="form-group">
                        <label for="old-password">Old Password</label>
                        <input type="password" id="old-password" name="old_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <input type="password" id="new-password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <p>Password must:</p>
                        <ul>
                            <li>Contain 8 to 32 characters</li>
                            <li>Contain at least one number and special character</li>
                            <li>Contain at least one upper and lower case</li>
                        </ul>
                    </div>
                    <div class="d-flex justify-content-evenly mb-2">
                        <input type="submit" value="Change Password" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php require('../inc/footer.php'); ?>

</body>

</html>