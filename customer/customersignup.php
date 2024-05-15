<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Customer Signup - CleckShopHub</title>
    <link rel="stylesheet" href="../css/signup.css">
</head>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
?>


<body>

    <?php
    include("../connection.php");
    include '../inc/header1.php';

    $error_message = '';
    $error_message1 = '';
    $error_message2 = '';
    $error_message3 = '';
    session_start();

    if (isset($_POST['signUp'])) {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $address = $_POST['address'];
        $contact = $_POST['contact_number'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];
        $dob = date('d/m/Y', strtotime($_POST['date_of_birth']));
        $gender = $_POST['gender'];
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['user_data'] = $_POST;

        if ($password != $confirmPassword) {
            $error_message = 'Password and Confirm Password do not match !!!.';
        } else if (strlen($confirmPassword) < 8 || strlen($confirmPassword) > 32) {
            $error_message = "Password should be 8 to 32 character long.<br>";
        } else if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $_POST['password'])) {
            $error_message = "Password should contain an uppercase,<br>Number<br>and a special character<br>";
        } else {
            $sql = "SELECT count(*) FROM \"USER\" WHERE username = '$username'";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            if ($row = oci_fetch_assoc($stid)) {
                $count = $row['COUNT(*)'];
            }
            if ($count != 0) {
                $error_message1 = 'Username Already Exists !!!.';
            } else {
                $sql = "SELECT count(*) FROM \"USER\" WHERE email = '$email'";
                $stid = oci_parse($connection, $sql);
                oci_execute($stid);
                if ($row = oci_fetch_assoc($stid)) {
                    $count = $row['COUNT(*)'];
                }
                if ($count != 0) {
                    $error_message2 = 'Email Already Exists !!!.';
                } else {
                    $sql = "SELECT count(*) FROM \"USER\" WHERE contact_number = '$contact'";
                    $stid = oci_parse($connection, $sql);
                    oci_execute($stid);
                    if ($row = oci_fetch_assoc($stid)) {
                        $count = $row['COUNT(*)'];
                    }
                    if ($count != 0) {
                        $error_message3 = 'Contact Number Already Exists !!!.';
                    } else {
                        $mail = new PHPMailer(true);

                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'cleckshophub@gmail.com';
                        $mail->Password = 'wxnc kjpg ypto rzyf';
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

                        $mail->setFrom('cleckshophub@gmail.com');

                        $mail->addAddress($email);

                        $mail->isHTML(true);

                        $mail->Subject = "OTP verification code.";

                        $message = "This is your OTP verification code $otp";
                        $mail->Body = $message;

                        $mail->send();

                        if ($mail) {
                            echo "OTP sent to your email. Please enter the OTP to verify.";
                            echo "<form method='POST'>
                                <input type='text' name='otp' placeholder='Enter OTP' required>
                                <button type='submit' name='verifyOtp'>Submit</button>
                            </form>";
                        } else {
                            $error_message =  "Email cannot be sent";
                        }
                    }
                }
            }
        }
    }

    if (isset($_POST['verifyOtp'])) {
        $enteredOtp = $_POST['otp'];
        $otp = $_SESSION['otp'];
        $user_data = $_SESSION['user_data'];

        if ($enteredOtp == $otp) {
            $username = $user_data['username'];
            $password = $user_data['password'];
            $email = $user_data['email'];
            $firstName = $user_data['first_name'];
            $lastName = $user_data['last_name'];
            $contact = $user_data['contact_number'];
            $address = $user_data['address'];
            $dob = date('d/m/Y', strtotime($user_data['date_of_birth']));
            $gender = $user_data['gender'];

            $sql = "INSERT INTO \"USER\" (User_id, Username, Password, Email, First_name, Last_name, Contact_number, Role, Created_date, Last_loggedin_date)
                    VALUES (null, '$username', '$password', '$email', '$firstName', '$lastName', '$contact', 'C', SYSDATE, null)";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);

            $sql = "SELECT user_id FROM \"USER\" WHERE username = '$username'";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            if ($row = oci_fetch_assoc($stid)) {
                $user_id = $row['USER_ID'];
            }

            $sql = "INSERT INTO customer VALUES('$user_id','$address',to_date('$dob','dd/mm/yyyy'),'$gender',null,1)";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            oci_close($connection);

            echo "<script>window.location.href = '../login/login.php';</script>";
        } else {
            echo "OTP did not match.";
        }
    }
    ?>
    <div class="container">
        <div class="form-container">
            <h2>Welcome to CleckShopHub,<br> Buy with Us</h2>
            <p>Want to become a seller? <a href="../trader/tradersignup.php">Click here</a></p>
            <form method="post">
                <div class="inline-fields">
                    <input type="text" name="first_name" placeholder="First Name" required value=<?php
                                                                                                    if (isset($_POST['first_name'])) {
                                                                                                        echo  $_POST['first_name'];
                                                                                                    }
                                                                                                    ?>>
                    <input type="text" name="last_name" placeholder="Last Name" required value=<?php
                                                                                                if (isset($_POST['last_name'])) {
                                                                                                    echo  $_POST['last_name'];
                                                                                                }
                                                                                                ?>>
                </div>
                <input type="text" name="address" placeholder="Address" required value=<?php
                                                                                        if (isset($_POST['address'])) {
                                                                                            echo  $_POST['address'];
                                                                                        }
                                                                                        ?>>
                <input type="text" name="contact_number" placeholder="Contact Number" required value=<?php
                                                                                                        if (isset($_POST['contact_number'])) {
                                                                                                            echo  $_POST['contact_number'];
                                                                                                        }
                                                                                                        ?>>
                <div class="error" style="color: red;"><?php if (!empty($error_message3)) echo "<p class='error'>$error_message3</p>"; ?></div>
                <input type="email" name="email" placeholder="Email" required value=<?php
                                                                                    if (isset($_POST['email'])) {
                                                                                        echo  $_POST['email'];
                                                                                    }
                                                                                    ?>>
                <div class="error" style="color: red;"><?php if (!empty($error_message2)) echo "<p class='error'>$error_message2</p>"; ?></div>
                <input type="text" name="username" placeholder="Username" required value=<?php
                                                                                            if (isset($_POST['username'])) {
                                                                                                echo  $_POST['username'];
                                                                                            }
                                                                                            ?>>
                <div class="error" style="color: red; text-align: left;"><?php if (!empty($error_message1)) echo "<p class='error'>$error_message1</p>"; ?></div>
                <div class="inline-fields">
                    <input type="date" name="date_of_birth" placeholder="Date of Birth" required value=<?php
                                                                                                        if (isset($_POST['date_of_birth'])) {
                                                                                                            echo  $_POST['date_of_birth'];
                                                                                                        }
                                                                                                        ?>>
                    <select name="gender">
                        <option value="" disabled selected>Gender</option>
                        <option value="M" <?php echo (isset($_POST['gender']) && $_POST['gender'] == "M") ? "selected" : "" ?>>Male</option>
                        <option value="F" <?php echo (isset($_POST['gender']) && $_POST['gender'] == "F") ? "selected" : "" ?>>Female</option>
                        <option value="O" <?php echo (isset($_POST['gender']) && $_POST['gender'] == "O") ? "selected" : "" ?>>Other</option>
                    </select>
                </div>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <div class="error" style="color: red;"><?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?></div>
                <label for="terms" class="terms-label">
                    <input type="checkbox" name="terms" id="terms" required>
                    I accept the <a href="../terms/terms.php">Terms and Conditions</a>.
                </label>
                <button type="submit" name="signUp">SIGN UP</button>
                <div class="center-text">
                    <br>
                    <p>Already have an account? <a href="../login/login.php">Login</a></p>
                </div>
            </form>
        </div>
        <div class="image-container">
        </div>
    </div>
</body>

</html>