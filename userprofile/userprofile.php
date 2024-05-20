<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('../inc/links.php'); ?>
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/userprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php
    include('../connection.php');
    session_start();

    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

    if (!$isLoggedIn || !isset($_SESSION['userID'])) {
        header('Location: login.php');
        exit;
    }

    // Fetch the user ID from the session
    $userID = $_SESSION['userID'];
    $error_message1 = $error_message2 = null;

    function getUserDetails($connection, $userID)
    {
        $sql = 'SELECT * FROM "USER" WHERE USER_ID = :userid';
        $stmt = oci_parse($connection, $sql);
        oci_bind_by_name($stmt, ':userid', $userID);
        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            echo "Error executing query: " . $e['message'];
            return false;
        }
        $result = oci_fetch_assoc($stmt);
        if (!$result) {
            echo "No user details found for USER_ID = $userID";
        }
        return $result;
    }

    $userDetails = getUserDetails($connection, $userID);

    if (!$userDetails) {
        exit;
    }

    $changesSaved = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstName = $_POST['first_name'] ?? $userDetails['FIRST_NAME'];
        $lastName = $_POST['last_name'] ?? $userDetails['LAST_NAME'];
        $username = $_POST['username'] ?? $userDetails['USERNAME'];
        $contactNumber = $_POST['contact_number'] ?? $userDetails['CONTACT_NUMBER'];

        if (!preg_match('/^\d{10}$/', $contactNumber)) {
            $contactNumberError = 'Contact number must be exactly 10 digits.';
        } 
        else{
            $sql = "SELECT count(*) FROM \"USER\" WHERE username = '$username'";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            if (!oci_execute($stid)) {
                $error = oci_error($stid);
                throw new Exception($error['message']);
            }

            if ($row = oci_fetch_assoc($stid)) {
                $count = $row['COUNT(*)'];
            }
            if ($count != 0) {
                $error_message1 = 'Username Already Exists !!!.';
            }  
            else {
                $sql = "SELECT count(*) FROM \"USER\" WHERE contact_number = '$contactNumber'";
                $stid = oci_parse($connection, $sql);
                oci_execute($stid);
                if (!oci_execute($stid)) {
                    $error = oci_error($stid);
                    throw new Exception($error['message']);
                }

                if ($row = oci_fetch_assoc($stid)) {
                    $count = $row['COUNT(*)'];
                }
                if ($count != 0) {
                    $error_message2 = 'Contact Number Already Exists !!!.';
                }
                else{
                    $updateSql = 'UPDATE "USER" SET FIRST_NAME = :firstname, LAST_NAME = :lastname, USERNAME = :username, CONTACT_NUMBER = :contactnumber WHERE USER_ID = :userid';
                    $updateStmt = oci_parse($connection, $updateSql);
                    oci_bind_by_name($updateStmt, ':firstname', $firstName);
                    oci_bind_by_name($updateStmt, ':lastname', $lastName);
                    oci_bind_by_name($updateStmt, ':username', $username);
                    oci_bind_by_name($updateStmt, ':contactnumber', $contactNumber);
                    oci_bind_by_name($updateStmt, ':userid', $userID);
                    if (!oci_execute($updateStmt)) {
                        $e = oci_error($updateStmt);
                        echo "Error executing update: " . $e['message'];
                    } else {
                        // Refresh user details after update
                        $userDetails = getUserDetails($connection, $userID);
                        $changesSaved = true;
                    }
                } 
            }   
        }
    }

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
            <a href="../logout/logout.php">Log out</a>
        </div>

        <!-- Profile Form-->
        <div class="main-content">
            <div class="profile-form">
                <h1>Profile</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="profile-info-form">
                    <div style="text-align: right;">
                        <button type="button" onclick="makeEditable()" class="btn btn-info" style="background-color: #007bff; border: none; color: white; padding: 5px 10px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; margin: 4px 2px; cursor: pointer; border-radius: 16px;"><i class="bi bi-pencil-square"></i> </button>
                    </div>
                    <div class="flex-row">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($userDetails['FIRST_NAME']); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($userDetails['LAST_NAME']); ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($userDetails['USERNAME']); ?>" disabled>
                    </div>
                    <div class="error" style="color: red;"><?php if (!empty($error_message2)) echo "<p class='error'>$error_message2</p>"; ?></div>
                    <div class="form-group">
                        <label for="contact-number">Contact Number</label>
                        <input type="number" id="contact-number" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($userDetails['CONTACT_NUMBER']); ?>" disabled>
                    </div>
                    <div class="error" style="color: red;"><?php if (!empty($error_message2)) echo "<p class='error'>$error_message2</p>"; ?></div>
                    <div class="d-flex justify-content-evenly mb-2">

                        <input type="submit" value="Save Changes" class="btn btn-primary" disabled>
                        <button type="button" onclick="window.location.reload()" class="btn btn-default" style="background-color: #6c757d; border: none; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 16px;">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function makeEditable() {
            var inputs = document.querySelectorAll('.profile-info-form input');
            inputs.forEach(function(input) {
                input.disabled = false;
            });
            document.querySelector('.profile-info-form input[type="submit"]').disabled = false;
        }

        <?php if ($changesSaved) : ?>
            alert('Changes saved successfully!');
        <?php endif; ?>
    </script>

    <?php require('../inc/footer.php'); ?>

</body>

</html>