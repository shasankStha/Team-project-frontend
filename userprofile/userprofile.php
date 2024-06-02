<?php
include('../connection.php');
session_start();

$isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

if (!$isLoggedIn || !isset($_SESSION['userID'])) {
    header('Location: ../login/login.php');
    exit;
}

$userID = $_SESSION['userID'];
$error_message1 = null;
$error_message2 = null;

function getUserDetails($connection, $userID)
{
    $sql = 'SELECT * FROM "USER" u INNER JOIN customer c ON c.user_id = u.user_id WHERE u.USER_ID = :userid';
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
    $profilePicture = $_POST['profile_picture'] ?? $userDetails['PROFILE_PICTURE'];

    $imgName = $_FILES['profile_picture']['name'];
    $imgSize = $_FILES['profile_picture']['size'];
    $imgType = $_FILES['profile_picture']['type'];
    $tmp = $_FILES['profile_picture']['tmp_name'];

    if (!preg_match('/^\d{10}$/', $contactNumber)) {
        $error_message2 = 'Contact number must be exactly 10 digits.';
    } else {
        $sql = "SELECT count(*) FROM \"USER\" WHERE username = '$username' AND user_id != '$userID'";
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
            $sql = "SELECT count(*) FROM \"USER\" WHERE contact_number = '$contactNumber' AND user_id != '$userID'";
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
                // Handle profile picture upload
                if (!empty($_FILES['profile_picture']['name'])) {
                    $validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (in_array($imgType, $validTypes)) {
                        $uploadFile = "image/" . $imgName;
                        if (move_uploaded_file($tmp, $uploadFile)) {
                            // Update profile picture in the database
                        } else {
                            $error_message2 = 'Error occurred while uploading picture.';
                        }
                    } else {
                        $error_message2 = 'Invalid file type. Only JPEG, JPG, PNG, and GIF are allowed.';
                    }
                }
                if (empty($imgName)) {
                    $imgName = $profilePicture;
                }


                $updateSql = 'UPDATE "USER" SET FIRST_NAME = :firstname, LAST_NAME = :lastname, USERNAME = :username, CONTACT_NUMBER = :contactnumber WHERE USER_ID = :userid';
                $updateSql2 = 'UPDATE "CUSTOMER" SET ADDRESS = :address, PROFILE_PICTURE = :profile_picture WHERE USER_ID = :userid';

                $updateStmt = oci_parse($connection, $updateSql);
                $updateStmt2 = oci_parse($connection, $updateSql2);

                oci_bind_by_name($updateStmt, ':firstname', $firstName);
                oci_bind_by_name($updateStmt, ':lastname', $lastName);
                oci_bind_by_name($updateStmt, ':username', $username);
                oci_bind_by_name($updateStmt, ':contactnumber', $contactNumber);
                oci_bind_by_name($updateStmt, ':userid', $userID);
                oci_bind_by_name($updateStmt2, ':address', $address);
                oci_bind_by_name($updateStmt2, ':profile_picture', $imgName);
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
                <div class="profile-header">
                    <h1>Profile</h1>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="profile-info-form">
                    <div style="text-align: right;">
                        <button type="button" onclick="makeEditable()" class="btn btn-info" style="background-color: #007bff; border: none; color: white; padding: 5px 10px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; margin: 4px 2px; cursor: pointer; border-radius: 16px;"><i class="bi bi-pencil-square"></i></button>
                    </div>
                    <br>
                    <div class="profile-picture">
                        <?php
                        $profilePicture = !empty($userDetails['PROFILE_PICTURE']) ? 'image/' . htmlspecialchars($userDetails['PROFILE_PICTURE']) : 'https://www.shutterstock.com/image-vector/user-profile-icon-vector-avatar-600nw-2247726673.jpg';
                        ?>
                        <img id="profile-image" src="<?php echo $profilePicture; ?>" alt="Profile Picture">
                        <input type="file" name="profile_picture" id="file-input" accept="image/*" style="display: none;">
                        <label for="file-input" id="upload-icon" class="upload-icon" style="display: none;">
                            <i class="fas fa-camera"></i>
                        </label>
                        <button type="button" id="delete-image" class="delete-icon" style="display: none;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <br>
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

            document.getElementById('upload-icon').style.display = 'inline-block';
            document.getElementById('delete-image').style.display = 'inline-block';
        }

        document.getElementById('file-input').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById('profile-image');
                img.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        document.getElementById('delete-image').addEventListener('click', function() {
            const img = document.getElementById('profile-image');
            img.src = 'https://i1.sndcdn.com/avatars-1qRoSxBGYeTS8vdw-VUsyDw-t240x240.jpg';
            document.getElementById('file-input').value = '';
        });

        <?php if ($changesSaved) : ?>
            alert('Changes saved successfully!');
        <?php endif; ?>

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('expanded');
        }
    </script>

    <?php require('../inc/footer.php'); ?>
</body>

</html>