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
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';
    ?>

    <?php
    include('../connection.php');
    include('../inc/header1.php');
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
    echo "<script>alert('Traders details not captured.')</script>";
    echo "<script>window.location.href = '../trader/tradersignup.php';</script>";
    exit();
}

$otp = rand(100000, 999999);

if (isset($_POST['signUpBtn'])) {
    $email = $_SESSION['traderSignupData']['email'];
    $shopname = $_POST['shop_name'];
    $contactnumber = $_POST['contact_number'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $_SESSION['shop_data'] = $_POST;

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cleckshophub@gmail.com';
    $mail->Password = 'wxnc kjpg ypto rzyf';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('biliyasmjh@gmail.com');

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject = "OTP verification code.";

    $message = "This is your OTP verification code $otp";
    $mail->Body = $message;

    if ($mail->send()) {
        $_SESSION['otp'] = $otp;
        echo "<div id='popup' class='popup'>
            <div class='popup-content'>
                <form method='POST'><p>Please enter the OTP to verify.</p>

                    <input type='text' name='otp' placeholder='Enter OTP' required>
                    <button type='submit' name='verifyOtp'>Submit</button>
                </form>
            </div>
          </div>";
    } else {
        $error_message = "Email cannot be sent";
    }
}

if (isset($_POST['verifyOtp'])) {
    $enteredOtp = $_POST['otp'];
    echo "<script>alert(Entered otp: $enteredOtp $otp)</script>";
    if ($enteredOtp == $_SESSION['otp']) {
        $firstname = $_SESSION['traderSignupData']['firstname'];
        $lastname = $_SESSION['traderSignupData']['lastname'];
        $address = $_SESSION['traderSignupData']['address'];
        $contact = $_SESSION['traderSignupData']['contact'];
        $email = $_SESSION['traderSignupData']['email'];
        $username = $_SESSION['traderSignupData']['username'];
        $confirmpassword = $_SESSION['traderSignupData']['password'];

        $shop_data = $_SESSION['shop_data'];
        $shopname = $shop_data['shop_name'];
        $contactnumber = $shop_data['contact_number'];
        $location = $shop_data['location'];
        $description = $shop_data['description'];


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
        unset($_SESSION['traderSignupData']);
        //Closing oracle connection
        oci_close($connection);

        echo "<script>window.location.href = '../login/login.php';</script>";
    } else {
        echo "OTP did not match.";
    }
}
?>
<script>
    //-----------popup for OTP verification---------------//
    // JavaScript to display the popup
    document.addEventListener('DOMContentLoaded', function() {
        // Show the popup
        document.getElementById('popup').style.display = 'block';
    });
</script>

</html>