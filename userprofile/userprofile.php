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
    $error_message1 = null;
    $error_message2 = null;

    function getUserDetails($connection, $userID)
    {
        $sql = 'SELECT * FROM "USER" u inner join customer c on c.user_id = u.user_id WHERE u.USER_ID = :userid';
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
        $address = $_POST['address'] ?? $userDetails['ADDRESS'];

        if (!preg_match('/^\d{10}$/', $contactNumber)) {
            $contactNumberError = 'Contact number must be exactly 10 digits.';
        } else {
            $sql = "SELECT count(*) FROM \"USER\" WHERE username = '$username' and user_id != '$userID'";
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
            } else {
                $sql = "SELECT count(*) FROM \"USER\" WHERE contact_number = '$contactNumber' and user_id != '$userID'";
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
                } else {
                    $updateSql = 'UPDATE "USER" SET FIRST_NAME = :firstname, LAST_NAME = :lastname, USERNAME = :username, CONTACT_NUMBER = :contactnumber WHERE USER_ID = :userid';
                    $updateSql2 = 'UPDATE "CUSTOMER" SET ADDRESS = :address WHERE USER_ID = :userid';

                    $updateStmt = oci_parse($connection, $updateSql);
                    $updateStmt2 = oci_parse($connection, $updateSql2);

                    oci_bind_by_name($updateStmt, ':firstname', $firstName);
                    oci_bind_by_name($updateStmt, ':lastname', $lastName);
                    oci_bind_by_name($updateStmt, ':username', $username);
                    oci_bind_by_name($updateStmt, ':contactnumber', $contactNumber);
                    oci_bind_by_name($updateStmt, ':userid', $userID);
                    oci_bind_by_name($updateStmt2, ':address', $address);
                    oci_bind_by_name($updateStmt2, ':userid', $userID);

                    if (!oci_execute($updateStmt) || !oci_execute($updateStmt2)) {
                        $e = oci_error($updateStmt);
                        $e2 = oci_error($updateStmt2);
                        echo "Error executing update: " . ($e ? $e['message'] : $e2['message']);
                    } else {
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
                <div class="profile-header">
                    <h1>Profile</h1>
                    <div class="profile-picture">
                        <img id="profile-image" src="https://i1.sndcdn.com/avatars-1qRoSxBGYeTS8vdw-VUsyDw-t240x240.jpg" alt="Profile Picture">
                        <input type="file" id="file-input" accept="image/*" style="display: none;">
                        <label for="file-input" class="upload-icon">
                            <i class="fas fa-camera"></i>
                        </label>
                        <button id="delete-image" class="delete-icon">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
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
                    <div class="error" style="color: red;"><?php if (!empty($error_message1)) echo "<p class='error'>$error_message1</p>"; ?></div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($userDetails['ADDRESS']); ?>" disabled>
                    </div>
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

        document.getElementById('file-input').addEventListener('change', function (event) {
            const reader = new FileReader();
            reader.onload = function () {
                const img = document.getElementById('profile-image');
                img.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        document.getElementById('delete-image').addEventListener('click', function () {
            const img = document.getElementById('profile-image');
            img.src = 'https://i1.sndcdn.com/avatars-1qRoSxBGYeTS8vdw-VUsyDw-t240x240.jpg';
            document.getElementById('file-input').value = '';
        });

        <?php if ($changesSaved) : ?>
            alert('Changes saved successfully!');
        <?php endif; ?>
    </script>

    <?php require('../inc/footer.php'); ?>

</body>

</html>
