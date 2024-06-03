<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/shopdetails.css">
    <title>Shop Details - CleckShopHub</title>
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .popup-content {
            background-color: #fff;
            width: 300px;
            padding: 20px;
            border-radius: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('popup').style.display = 'block';
        });
    </script>
</head>

<body>
    <?php
    session_start();
    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    $error_message = '';

    include('../connection.php');
    include('../inc/header1.php');

    if (!isset($_SESSION['traderSignupData'])) {
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


        if (strlen($contactnumber) != 10) {
            $error_message = "Contact number should have 10 digits.";
        } else {
            $sql = "select count(*) from shop where UPPER(shop_name) = UPPER('$shopname') or contact_number = '$contactnumber'";
            $stid = oci_parse($connection, $sql);
            $exe = oci_execute($stid);
            $count = 0;
            if ($row = oci_fetch_assoc($stid)) {
                $count = $row['COUNT(*)'];
            }
            if ($count > 0) {
                $error_message = "Shop Name or Contact Number already exists!!!";
            } else {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                try {
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
                    $mail->Body = "This is your OTP verification code $otp";

                    if ($mail->send()) {
                        $_SESSION['otp'] = $otp;
                        echo "<div id='popup' class='popup' style='display: block;'>
                            <div class='popup-content'>
                                <form method='POST'>
                                    <p>Please enter the OTP to verify.</p>
                                    <input type='text' name='otp' placeholder='Enter OTP' required>
                                    <button type='submit' name='verifyOtp'>Submit</button>
                                </form>
                            </div>
                        </div>";
                    } else {
                        $error_message = "Email could not be sent.";
                    }
                } catch (Exception $e) {
                    $error_message = "Mailer Error: " . $mail->ErrorInfo;
                }
            }
        }
    }

    if (isset($_POST['verifyOtp'])) {
        $enteredOtp = $_POST['otp'];
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



            try {
                $sql = "INSERT INTO \"USER\" (User_id, Username, Password, Email, First_name, Last_name, Contact_number, Role, Created_date, Last_loggedin_date)
                        VALUES (null, '$username', '$confirmpassword', '$email', '$firstname', '$lastname', '$contact', 'T', SYSDATE, null)";
                $stid = oci_parse($connection, $sql);
                $exe = oci_execute($stid);
                if (!$exe) {
                    $error = oci_error($stid);
                    throw new Exception($error['message']);
                }

                $sql = "SELECT user_id FROM \"USER\" WHERE username = '$username'";
                $stid = oci_parse($connection, $sql);
                $exe = oci_execute($stid);
                if (!$exe) {
                    $error = oci_error($stid);
                    throw new Exception($error['message']);
                }

                $user_id = null;
                if ($row = oci_fetch_assoc($stid)) {
                    $user_id = $row['USER_ID'];
                } else {
                    throw new Exception("User not found");
                }

                $sql = "INSERT INTO TRADER VALUES ('$user_id', '$address', 0)";
                $stid = oci_parse($connection, $sql);
                $exe = oci_execute($stid);
                if (!$exe) {
                    $error = oci_error($stid);
                    throw new Exception($error['message']);
                }

                $sql = "INSERT INTO SHOP VALUES (null, '$shopname','$description','$location',null,'$contactnumber', '$user_id')";
                $stid = oci_parse($connection, $sql);
                $exe = oci_execute($stid);
                if (!$exe) {
                    $error = oci_error($stid);
                    throw new Exception($error['message']);
                }

                unset($_SESSION['traderSignupData']);
                oci_close($connection);

                echo "<script>window.location.href = '../login/login.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('An error occurred: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('OTP did not match.');</script>";
        }
    }
    ?>

    <div class="container mt-3">
        <div class="form-container">
            <h2>Welcome to CleckShopHub,<br> Enter your shop details</h2>
            <br>
            <form method="post" class="needs-validation" novalidate>
                <input type="text" class="form-control mb-3" name="shop_name" placeholder="Shop Name" required value="<?php echo isset($_POST['shop_name']) ? $_POST['shop_name'] : ''; ?>">
                <input type="number" class="form-control mb-3" name="contact_number" placeholder="Contact Number" required value="<?php echo isset($_POST['contact_number']) ? $_POST['contact_number'] : ''; ?>">
                <input type="text" class="form-control mb-3" name="location" placeholder="Location" required value="<?php echo isset($_POST['location']) ? $_POST['location'] : ''; ?>">
                <textarea class="form-control mb-3" name="description" placeholder="Description" rows="7" required><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                <label for="terms" class="terms-label">
                    <input type="checkbox" name="terms" id="terms" required>
                    I accept the <a href="../terms/terms.php">Terms and Conditions</a>.
                </label>
                <div class="error" style="color: red; text-align: left;"><?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?></div>
                <button type="submit" class="btn btn-primary" name="signUpBtn">Sign Up</button>
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="../customer/customerlogin.php">Login</a></p>
                </div>
            </form>
        </div>
        <div class="image-container">
            <!-- Image container content -->
        </div>
    </div>

    <!-- Include Bootstrap JavaScript for form validation -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>