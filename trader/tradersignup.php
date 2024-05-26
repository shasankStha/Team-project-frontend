<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <title>Trader Signup - CleckShopHub</title>
    <link rel="stylesheet" href="../css/signup.css">
</head>

<body>
    <?php
    session_start();
    include("../connection.php");
    include('../inc/header1.php');

    $error_message = '';
    $error_message1 = '';
    $error_message2 = '';
    $error_message3 = '';

    if (isset($_POST['signUp'])) {
        try {
            $sql = "SELECT count(*) as trader_count FROM \"TRADER\"";
            $stid = oci_parse($connection, $sql);
            $exe = oci_execute($stid);
            if (!$exe) {
                $error = oci_error($stid);
                throw new Exception($error['message']);
            }
            $traderCount = null;
            if ($row = oci_fetch_assoc($stid)) {
                $traderCount = $row['TRADER_COUNT'];
            }

            if ($traderCount == 10) {
                echo "<script>alert('Number of traders is already 10 and cannot register.')</script>";
                echo "<script>window.location.href = '../trader/tradersignup.php';</script>";
                exit;
            } else {
                $firstName = $_POST['first_name'];
                $lastName = $_POST['last_name'];
                $address = $_POST['address'];
                $contact = $_POST['contact_number'];
                $email = $_POST['email'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirm_password'];

                if ($password != $confirmPassword) {
                    $error_message = 'Password and Confirm Password do not match !!!.';
                } else if (strlen($contact) != 10) {
                    $error_message = 'Contact number should have 10 digit number.';
                } else if (strlen($confirmPassword) < 8 || strlen($confirmPassword) > 32) {
                    $error_message = "Password should be 8 to 32 characters long.<br>";
                } else if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $_POST['password'])) {
                    $error_message = "Password should contain an uppercase,<br>Number<br>and a special character<br>";
                } else {
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
                    } else {
                        $sql = "SELECT count(*) FROM \"USER\" WHERE email = '$email'";
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
                            $error_message2 = 'Email Already Exists !!!.';
                        } else {
                            $sql = "SELECT count(*) FROM \"USER\" WHERE contact_number = '$contact'";
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
                                $error_message3 = 'Contact Number Already Exists !!!.';
                            } else {
                                $_SESSION['traderSignupData'] = array(
                                    'firstname' => $firstName,
                                    'lastname' => $lastName,
                                    'address' => $address,
                                    'contact' => $contact,
                                    'email' => $email,
                                    'username' => $username,
                                    'password' => $confirmPassword
                                );
                                echo "<script>window.location.href = '../shops/shopdetails.php';</script>";
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $error_message = "An error occured: " . $e->getMessage();
        }
    }
    ?>

    <div class="container mt-3">
        <div class="form-container">
            <h2>Welcome to CleckShopHub,<br> Sell with Us</h2>
            <p>Want to buy local products? <a href="../customer/customersignup.php">Click here</a></p>
            <br>
            <form method="post" class="needs-validation" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="first_name" placeholder="First Name" required value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="last_name" placeholder="Last Name" required value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : ''; ?>" required>
                    </div>
                </div>
                <input type="text" class="form-control mb-3" name="address" placeholder="Address" required value="<?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?>" required>
                <input type="number" class="form-control mb-3" name="contact_number" placeholder="Contact Number" required value="<?php echo isset($_POST['contact_number']) ? $_POST['contact_number'] : ''; ?>" required>
                <div class="error" style="color: red;"><?php if (!empty($error_message3)) echo "<p class='error'>$error_message3</p>"; ?></div>
                <input type="email" class="form-control mb-3" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                <div class="error" style="color: red;"><?php if (!empty($error_message2)) echo "<p class='error'>$error_message2</p>"; ?></div>
                <input type="text" class="form-control mb-3" name="username" placeholder="Username" required value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
                <div class="error" style="color: red;"><?php if (!empty($error_message1)) echo "<p class='error'>$error_message1</p>"; ?></div>
                <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>
                <input type="password" class="form-control mb-3" name="confirm_password" placeholder="Confirm Password" required>
                <div class="error" style="color: red; text-align: left;"><?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?></div>
                <button type="submit" class="btn btn-primary" name="signUp">Next</button>
                <br>
                <div class="text-center mt-3">
                    <p>Already have an account? <a href="../login/login.php">Login</a></p>
                </div>
            </form>
        </div>
        <div class="image-container" style="background: url('../images/traderregister.jpeg') center/cover no-repeat;"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
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