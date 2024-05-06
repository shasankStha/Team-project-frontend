<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/shopdetails.css">
    <title>Shopdetails - CleckShopHub</title>

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

    <div class="container">
        <div class="form-container">
            <h2>Welcome to CleckShopHub,<br> Enter your shop details</h2>
            <br>
            <form method="post">

                <input type="text" name="shop_name" placeholder="Shop Name" required>
                <input type="text" name="contact_number" placeholder="Contact Number" required>
                <input type="text" name="location" placeholder="Location" required>
                <textarea name="description" placeholder="Description" rows="7" required></textarea>

                <br>
                <label for="terms" class="terms-label">
                    <input type="checkbox" name="terms" id="terms" required>
                    I accept the <a href="../terms/terms.php">Terms and Conditions</a>.
                </label>
                <button type="submit" name="signUpBtn">Sign Up</button>
                <div class="center-text">
                    <br>
                    <p>Already have an account? <a href="../customer/customerlogin.php">Login</a></p>
                </div>
            </form>
        </div>
        <div class="image-container">
            <!-- Image container content -->
        </div>
    </div>

</body>

<?php
if (!isset($_SESSION['traderSignupData'])) {
    // Redirect to tradersignup.php if data is not present
    echo "<script>window.location.href = '../trader/tradersignup.php';</script>";
}

if (isset($_POST['signUpBtn'])) {
    $firstname = $_SESSION['traderSignupData']['firstname'];
    $lastname = $_SESSION['traderSignupData']['lastname'];
    $address = $_SESSION['traderSignupData']['address'];
    $contact = $_SESSION['traderSignupData']['contact'];
    $email = $_SESSION['traderSignupData']['email'];
    $username = $_SESSION['traderSignupData']['username'];
    $confirmpassword = $_SESSION['traderSignupData']['password'];
    $shopname = $_POST['shop_name'];
    $contactnumber = $_POST['contact_number'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    //Insert in User table
    $sql = "INSERT INTO \"USER\" (User_id, Username, Password, Email, First_name, Last_name, Contact_number, Role, Created_date, Last_loggedin_date)
        VALUES (null, '$username', '$confirmpassword', '$email', '$firstname', '$lastname', '$contact', 'T', SYSDATE, null)";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);

    //Getting user_id
    $sql = "select user_id from \"USER\" where username = '$username'";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $user_id = null;
    if ($row = oci_fetch_assoc($stid)) {
        $user_id = $row['USER_ID'];
    } else {
        echo "User not found";
    }

    //Adding data in trader table
    $sql = "INSERT INTO TRADER VALUES ('$user_id', '$address', 0)";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);

    //Adding data in shop table
    $sql = "INSERT INTO SHOP VALUES (null, '$shopname','$description','$location',null,'$contactnumber', '$user_id')";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);

    //Destroying the session global variable
    unset($_SESSION['trader_signup_data']);
    //Closing oracle connection
    oci_close($connection);

    echo "<script>window.location.href = '../login/login.php';</script>";
}
?>

</html>