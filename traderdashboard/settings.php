<?php
session_start();
require('../connection.php');

if (!isset($_SESSION['traderUser']) || $_SESSION['loggedinTrader'] !== TRUE) {
    header('Location: ../login/login.php');
    exit;
}

$userID = $_SESSION['traderID'];
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
    return oci_fetch_assoc($stmt);
}
$userDetails = getUserDetails($connection, $userID);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $username = $_POST['username'];
    $contactNumber = $_POST['contact_number'];

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
        echo "<script>alert('Changes saved successfully!');</script>";
    }
}

$shopID = $_SESSION['shopID'];
$shopDetailsQuery = 'SELECT SHOP_NAME, SHOP_DESCRIPTION, LOCATION, CONTACT_NUMBER, PICTURE FROM "SHOP" WHERE SHOP_ID = :shopid';
$shopStmt = oci_parse($connection, $shopDetailsQuery);
oci_bind_by_name($shopStmt, ':shopid', $shopID);
oci_execute($shopStmt);
$shopDetails = oci_fetch_assoc($shopStmt);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_shop'])) {
    $shopName = $_POST['shop_name'];
    $shopDescription = $_POST['shop_description'];
    $location = $_POST['location'];
    $contactNumber = $_POST['contact_number'];
    $shopID = $_SESSION['shopID'];
    $image = $shopDetails['PICTURE'];

    // Handle image upload
    if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];  // Allowed file types
        $filename = $_FILES['shop_image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array(strtolower($filetype), $allowed)) {
            $newFilename = uniqid('IMG-', true) . '.' . $filetype;
            $uploadDir = '../shop_image/';
            $destination = $uploadDir . $newFilename;

            if (move_uploaded_file($_FILES['shop_image']['tmp_name'], $destination)) {
                $image = $newFilename;
            } else {
                echo "<script>alert('Error uploading file.');</script>";
                $image = null;
            }
        } else {
            echo "<script>alert('Invalid file type.');</script>";
            $image = null;
        }
    } else {
        // If no new image is uploaded, use the existing one
        $image = $shopDetails['PICTURE'];
    }

    // Update shop details including the new image if available
    $updateShopSql = 'UPDATE "SHOP" SET SHOP_NAME = :shopname, SHOP_DESCRIPTION = :shopdescription, LOCATION = :location, CONTACT_NUMBER = :contactnumber, PICTURE = :picture WHERE SHOP_ID = :shopid';
    $updateShopStmt = oci_parse($connection, $updateShopSql);
    oci_bind_by_name($updateShopStmt, ':shopname', $shopName);
    oci_bind_by_name($updateShopStmt, ':shopdescription', $shopDescription);
    oci_bind_by_name($updateShopStmt, ':location', $location);
    oci_bind_by_name($updateShopStmt, ':contactnumber', $contactNumber);
    oci_bind_by_name($updateShopStmt, ':picture', $image);
    oci_bind_by_name($updateShopStmt, ':shopid', $shopID);
    if (oci_execute($updateShopStmt)) {
        echo "<script>alert('Shop details updated successfully!');</script>";
        // Re-fetch details to update the display without needing a page refresh
        oci_execute($shopStmt);
        $shopDetails = oci_fetch_assoc($shopStmt);
    } else {
        $e = oci_error($updateShopStmt);
        echo "Error updating shop details: " . $e['message'];
    }
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
                            // echo "<script>alert('Your password has been changed successfully.')</script>";
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


// HTML and form start here
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Panel - Settings</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('traderdashboardheader.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">SETTINGS</h3>

                <!-- Profile settings section -->

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Profile Settings</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#profile-settings">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </div>
                        <h6 class="card-subtitle mb-1 fw-bold">Name</h6>
                        <p class="card-text" id="shop_title">
                            <?php echo htmlspecialchars($userDetails['FIRST_NAME']); ?>
                            <?php echo htmlspecialchars($userDetails['LAST_NAME']); ?>
                        </p>
                        <h6 class="card-subtitle mb-1 fw-bold">Username</h6>
                        <p class="card-text" id="shop_description">
                            <?php echo htmlspecialchars($userDetails['USERNAME']); ?>
                        </p>
                        <h6 class="card-subtitle mb-1 fw-bold">Contact Number</h6>
                        <p class="card-text" id="shop_description">
                            <?php echo htmlspecialchars($userDetails['CONTACT_NUMBER']); ?>
                        </p>
                    </div>
                </div>

                <!-- Profile settings modal -->

                <div class="modal fade" id="profile-settings" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="profileSettingsLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="profile_settings_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Profile</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="first-name" class="form-label fw-bold">First Name</label>
                                        <input type="text" id="first-name" name="first_name" class="form-control shadow-none" required value="<?php echo htmlspecialchars($userDetails['FIRST_NAME']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="last-name" class="form-label fw-bold">Last Name</label>
                                        <input type="text" id="last-name" name="last_name" class="form-control shadow-none" required value="<?php echo htmlspecialchars($userDetails['LAST_NAME']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label fw-bold">Username</label>
                                        <input type="text" id="username" name="username" class="form-control shadow-none" value="<?php echo htmlspecialchars($userDetails['USERNAME']); ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="contact-number" class="form-label fw-bold">Contact Number</label>
                                        <input type="number" id="contact-number" name="contact_number" class="form-control shadow-none" value="<?php echo htmlspecialchars($userDetails['CONTACT_NUMBER']); ?>">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                                    <button type="submit" name="update_profile" class="btn btn-dark shadow-none">SUBMIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Shop settings modal -->

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Shop Settings</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#shop-settings-modal">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </div>
                        <h6 class="card-subtitle mb-1 fw-bold">Shop Name</h6>
                        <p class="card-text"><?php echo htmlspecialchars($shopDetails['SHOP_NAME']); ?></p>
                        <h6 class="card-subtitle mb-1 fw-bold">Description</h6>
                        <p class="card-text"><?php echo htmlspecialchars($shopDetails['SHOP_DESCRIPTION']); ?></p>
                        <h6 class="card-subtitle mb-1 fw-bold">Location</h6>
                        <p class="card-text"><?php echo htmlspecialchars($shopDetails['LOCATION']); ?></p>
                        <h6 class="card-subtitle mb-1 fw-bold">Contact Number</h6>
                        <p class="card-text"><?php echo htmlspecialchars($shopDetails['CONTACT_NUMBER']); ?></p>
                    </div>
                </div>

                <!-- Modal for Editing Shop Details -->
                <div class="modal fade" id="shop-settings-modal" tabindex="-1" aria-labelledby="shopSettingsModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="shopSettingsModalLabel">Edit Shop Details</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="shop-name" class="form-label">Shop Name</label>
                                        <input type="text" class="form-control" id="shop-name" name="shop_name" required value="<?php echo htmlspecialchars($shopDetails['SHOP_NAME']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="shop-description" class="form-label">Description</label>
                                        <textarea class="form-control" id="shop-description" name="shop_description" rows="3"><?php echo htmlspecialchars($shopDetails['SHOP_DESCRIPTION']); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($shopDetails['LOCATION']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact-number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" id="contact-number" name="contact_number" value="<?php echo htmlspecialchars($shopDetails['CONTACT_NUMBER']); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="shop-image" class="form-label">Shop Image</label>
                                        <input type="file" class="form-control" id="shop-image" name="shop_image">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn text-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" name="update_shop" class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- password settings section -->

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Change Password</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#password-settings">
                                <i class="bi bi-pencil-square"></i> Change your password
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Password Change Modal -->
                <?php

                if (isset($_POST['changePasswordSubmit'])) {
                    $oldPassword = $_POST['old_password'];
                    $newPassword = $_POST['new_password'];
                    $confirmPassword = $_POST['confirm_password'];

                    if ($newPassword !== $confirmPassword) {
                        echo "<script>alert('Password and Confirm Password do not match.')</script>";
                    } else if (strlen($confirmPassword) < 8 || strlen($confirmPassword) > 32) {
                        echo "<script>alert('Password should be 8 to 32 characters long.')</script>";
                    } else if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $newPassword)) {
                        echo "<script>alert('Password criteria didn\'t match.')</script>";
                    } else {
                        try {
                            // Prepare the SQL statement with parameterized query
                            $sql = "SELECT COUNT(*) AS CNT FROM \"USER\" WHERE password = password_encrypt(:oldPassword) AND user_id = :userID";
                            $stid = oci_parse($connection, $sql);
                            oci_bind_by_name($stid, ':oldPassword', $oldPassword);
                            oci_bind_by_name($stid, ':userID', $userID);
                            oci_execute($stid);

                            if ($row = oci_fetch_assoc($stid)) {
                                $count = $row['CNT'];

                                if ($count == 1) {
                                    try {
                                        // Prepare the update statement
                                        $sql = "UPDATE \"USER\" SET password = password_encrypt(:newPassword) WHERE user_id = :userID";
                                        $stid = oci_parse($connection, $sql);
                                        oci_bind_by_name($stid, ':newPassword', $confirmPassword);
                                        oci_bind_by_name($stid, ':userID', $userID);
                                        $exe = oci_execute($stid);

                                        if ($exe) {
                                            echo "<script>alert('Password has been changed successfully.')</script>";
                                        } else {
                                            echo "<script>alert('An error occurred while updating the password')</script>";
                                        }
                                    } catch (Exception $e) {
                                        echo "<script>alert('An error occurred')</script>";
                                    }
                                } else {
                                    echo "<script>alert('Old password didn\'t match.')</script>";
                                }
                            } else {
                                echo "<script>alert('An error occurred while verifying the old password')</script>";
                            }
                        } catch (Exception $e) {
                            echo "<script>alert('An error occurred')</script>";
                        }
                    }
                }

                ?>


                <div class="modal fade" id="password-settings" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="password_change_form" method="post">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Change Password</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="old-password" class="form-label fw-bold">Old Password</label>
                                        <input type="password" id="old-password" name="old_password" class="form-control shadow-none" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new-password" class="form-label fw-bold">New Password</label>
                                        <input type="password" id="new-password" name="new_password" class="form-control shadow-none" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm-password" class="form-label fw-bold">Confirm
                                            Password</label>
                                        <input type="password" id="confirm-password" name="confirm_password" class="form-control shadow-none" required>
                                    </div>
                                    <div class="error" style="color: red;">

                                    </div>
                                    <div class="mb-3">
                                        <p class="fw-bold">Password must:</p>
                                        <ul>
                                            <li>Contain 8 to 32 characters</li>
                                            <li>Contain at least one number and special character</li>
                                            <li>Contain at least one upper and lower case</li>
                                        </ul>
                                    </div>
                                    <div class="success" style="color: white; background-color: green; border-radius:8px;
                    width:100%;max-width:400px;text-align:center;">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                                    <button type="submit" class="btn btn-dark shadow-none" name="changePasswordSubmit">CHANGE PASSWORD</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>





            </div>
        </div>

</body>

</html>