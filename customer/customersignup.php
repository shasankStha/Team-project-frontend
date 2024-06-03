<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/signup.css">
    <title>Customer Signup - CleckShopHub</title>
    <style>
        /*-----------------for OTP popup----------------*/
        /* Styles for the popup */
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
        // JavaScript to display the popup
        document.addEventListener('DOMContentLoaded', function() {
            // Show the popup
            document.getElementById('popup').style.display = 'block';
        });
    </script>
</head>

<body>
    <?php
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';
    include("../connection.php");
    include '../inc/header1.php';

    $error_message = '';
    $error_message1 = '';
    $error_message2 = '';
    $error_message3 = '';

    if (isset($_POST['signUp'])) {
        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);
        $address = trim($_POST['address']);
        $contact = trim($_POST['contact_number']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);
        $dob = date('d/m/y', strtotime($_POST['date_of_birth']));
        $gender = $_POST['gender'];
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['user_data'] = $_POST;


        date_default_timezone_set('Asia/Kathmandu');
        $dobDate = DateTime::createFromFormat('d/m/Y', $dob);
        $currentDate = new DateTime();
        $age = $currentDate->diff($dobDate)->y;

        if ($password != $confirmPassword) {
            $error_message = 'Password and Confirm Password do not match !!!.';
        } else if ($age <= 12) {
            $error_message = 'You are not eligible to register.';
        } else if (strlen($contact) != 10) {
            $error_message = 'Contact number should have 10 digit number.';
        } else if (strlen($confirmPassword) < 8 || strlen($confirmPassword) > 32) {
            $error_message = "Password should be 8 to 32 characters long.<br>";
        } else if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $_POST['password'])) {
            $error_message = "Password should contain an uppercase letter,<br>a number,<br>and a special character<br>";
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
                            // Display the popup
                            echo "<div id='popup' class='popup'>
                                    <div class='popup-content'>
                                        <form method='POST'><p>Please enter the OTP to verify.</p>
                                            <input type='text' name='otp' placeholder='Enter OTP' required>
                                            <button type='submit' name='verifyOtp'>Submit</button>
                                        </form>
                                    </div>
                                  </div>";
                        } else {
                            $error_message =  "Email cannot be sent";
                        }
                    }
                }
            }
        }
    }

    if (isset($_POST['verifyOtp'])) {
        try {
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
                if (!$stid) {
                    $e = oci_error($connection);
                    throw new Exception($e['message']);
                }
                oci_execute($stid);

                $sql = "SELECT user_id FROM \"USER\" WHERE username = '$username'";
                $stid = oci_parse($connection, $sql);
                if (!$stid) {
                    $e = oci_error($connection);
                    throw new Exception($e['message']);
                }
                oci_execute($stid);

                if ($row = oci_fetch_assoc($stid)) {
                    $user_id = $row['USER_ID'];
                }
                $sql = "INSERT INTO customer VALUES('$user_id','$address',to_date('$dob','dd/mm/yyyy'),'$gender',null,1)";
                $stid = oci_parse($connection, $sql);
                if (!$stid) {
                    $e = oci_error($connection);
                    throw new Exception($e['message']);
                }
                oci_execute($stid);
                oci_close($connection);

                session_destroy();

                echo "<script>window.location.href = '../login/login.php';</script>";
            } else {
                echo "<script>alert('OTP did not match')</script>";
            }
        } catch (Exception $e) {
            echo "<div class='error' style='color: red;'>Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>

    <div class="container mt-3">
        <div class="form-container">
            <h2>Welcome to CleckShopHub, Buy with Us</h2>
            <p>Want to become a seller? <a href="../trader/tradersignup.php">Click here</a></p>
            <form method="post" class="needs-validation" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="first_name" placeholder="First Name" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : NULL; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="last_name" placeholder="Last Name" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : NULL; ?>" required>
                    </div>
                </div>
                <input type="text" class="form-control mb-3" name="address" placeholder="Address" value="<?php echo isset($_POST['address']) ? $_POST['address'] : NULL; ?>" required>
                <input type="number" class="form-control mb-3" name="contact_number" placeholder="Contact Number" value="<?php echo isset($_POST['contact_number']) ? $_POST['contact_number'] : NULL; ?>" min="1000000000" required>
                <div class="error" style="color: red;"><?php if (!empty($error_message3)) echo "<p class='error'>$error_message3</p>"; ?></div>
                <input type="email" class="form-control mb-3" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : NULL; ?>" required>
                <div class="error" style="color: red;"><?php if (!empty($error_message2)) echo "<p class='error'>$error_message2</p>"; ?></div>
                <input type="text" class="form-control mb-3" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : NULL; ?>" required>
                <div class="error" style="color: red; text-align: left;"><?php if (!empty($error_message1)) echo "<p class='error'>$error_message1</p>"; ?></div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="date" class="form-control" name="date_of_birth" value="<?php echo isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : NULL; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <select name="gender" class="form-select" required>
                            <option value="">Gender</option>
                            <option value="M" <?php echo (isset($_POST['gender']) && $_POST['gender'] == "M") ? "selected" : ""; ?>>Male</option>
                            <option value="F" <?php echo (isset($_POST['gender']) && $_POST['gender'] == "F") ? "selected" : ""; ?>>Female</option>
                            <option value="O" <?php echo (isset($_POST['gender']) && $_POST['gender'] == "O") ? "selected" : ""; ?>>Other</option>
                        </select>
                    </div>
                </div>
                <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>
                <input type="password" class="form-control mb-3" name="confirm_password" placeholder="Confirm Password" required>
                <div class="error" style="color: red;"><?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?></div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">I accept the <a href="../terms/terms.php">Terms and Conditions</a>.</label>
                </div>
                <button type="submit" class="btn btn-primary" name="signUp">SIGN UP</button>
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="../login/login.php">Login</a></p>
                </div>
            </form>
        </div>
        <div class="image-container" style="background: url('../images/customersignup.jpeg') center/cover no-repeat;"></div>
    </div>

    <!-- Include Bootstrap JavaScript for form validation -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <!-- Custom script to enforce Bootstrap validation -->
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>

</html>