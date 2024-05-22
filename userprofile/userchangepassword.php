<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('../inc/links.php'); ?>
    <title>User profile</title>
    <link rel="stylesheet" href="../css/userchangepassword.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php
    session_start();
    include('../connection.php');

    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
    }

    $error_message = "";
    $message = "";

    if (isset($_POST['submit'])) {
        $user_id = $_SESSION['userID'];
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword != $confirmPassword) {
            $error_message = 'Password and Confirm Password do not match.';
        } else if (strlen($confirmPassword) < 8 || strlen($confirmPassword) > 32) {
            $error_message = "Password should be 8 to 32 characters long.";
        } else if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $confirmPassword)) {
            $error_message = "Password criteria didn't match.";
        } else {
            try {
                $sql = "SELECT COUNT(*) AS CNT FROM \"USER\" WHERE password = password_encrypt('$oldPassword') AND user_id = '$user_id'";
                $stid = oci_parse($connection, $sql);
                oci_execute($stid);

                if ($row = oci_fetch_assoc($stid)) {
                    $count = $row['CNT'];

                    if ($count == 1) {
                        try {
                            $sql = "UPDATE \"USER\" SET password = password_encrypt('$confirmPassword') WHERE user_id = '$user_id'";
                            $stid = oci_parse($connection, $sql);
                            $exe = oci_execute($stid);

                            if ($exe) {
                                $message = "Password has been changed successfully.";
                            } else {
                                $error_message = "An error occurred while updating the password.";
                            }
                        } catch (Exception $e) {
                            $error_message = "An error occurred: " . $e->getMessage();
                        }
                    } else {
                        $error_message = "Old password didn't match.";
                    }
                } else {
                    $error_message = "An error occurred while verifying the old password.";
                }
            } catch (Exception $e) {
                $error_message = "An error occured: " . $e->getMessage();
            }
        }
    }
    ?>

    <!-- Side Bar-->
    <div class="container">
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <a href="userprofile.php">Profile</a>
            <a href="userorderhistory.php">Orders History</a>
            <a href="userfavourites.php">Favourites</a>
            <a href="userchangepassword.php">Change Password</a>
            <a href="../logout/logout.php">Log out</a>
        </div>

        <div class="main-content">
            <div class="profile-form">
                <h1>Change Password</h1>
                <form method="post" class="password-change-form">
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
                    <div class="error" style="color: red;">
                        <?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?>
                    </div>
                    <div class="form-group">
                        <p>Password must:</p>
                        <ul>
                            <li>Contain 8 to 32 characters</li>
                            <li>Contain at least one number and special character</li>
                            <li>Contain at least one upper and lower case</li>
                        </ul>
                    </div>
                    <div class="success" style="color: white; background-color: green; border-radius:8px;
                    width:100%;max-width:400px;text-align:center;">
                        <?php if (!empty($message)) echo "<p class='success'>$message</p>"; ?>
                    </div>
                    <div class="d-flex justify-content-evenly mb-2">
                        <input type="submit" name="submit" value="Change Password" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require('../inc/footer.php'); ?>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('expanded');
        }
    </script>
</body>

</html>
