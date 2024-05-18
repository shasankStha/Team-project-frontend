<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Forgot Password</title>

</head>
<style>
    :root {
        --primary-color: black;
        --secondary-color: #f5f7f6;
    }

    * {
        margin: 0;
        padding: 0;
        color: var(--primary-color);
    }

    body {
        font-family: 'Poppins', sans-serif;
        font-weight: 300;
        background: var(--secondary-color);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    #loginSection {
        display: flex;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin-top: 40px;
    }

    #formContent {
        flex: 1;
        padding: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    #formIntro {
        font-size: 28px;
        margin-bottom: 24px;
        text-align: center;
    }

    #formIntroDesc {
        margin-bottom: 24px;
        text-align: center;
    }

    #loginForm {
        width: 100%;
        max-width: 350px;
    }

    .inputBx {
        margin-bottom: 24px;
    }

    .inputBx input {
        width: 100%;
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        margin-top: 8px;
    }

    .inputBx input[type="submit"] {
        background: var(--primary-color);
        color: #ffffff;
        border: none;
        cursor: pointer;
        font-weight: 500;
        margin-top: 300px;
        margin-right: 20px;
        transition: background 0.3s ease;
    }

    .inputBx label {
        font-size: 16px;
        cursor: pointer;
    }

    .inputBx input[type="checkbox"] {
        vertical-align: middle;
        margin: 0;
        padding: 0;
        width: 16px;
        height: 16px;
    }

    .inputBx label span {
        margin-left: -4px;
    }

    .inputBx input[type="submit"]:hover {
        background: #333;
    }

    #forgotPasswordLink {
        display: block;
        text-align: center;
        margin-top: 16px;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        #loginSection {
            flex-direction: column;
            width: 100%;
            max-width: 500px;

        }

        #formContent {
            padding: 20px;
        }

        #loginForm {
            max-width: 100%;
        }
    }

    @media (max-width: 480px) {
        #formContent {
            padding: 10px;
        }
    }

    /*----------------for OTP popup-----------------*/
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

<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
?>

<?php
if (isset($_POST['validateEmail'])) {
    include("../connection.php");

    $enteredEmail = $_POST['email'];
    $_SESSION['enteredEmail'] = $enteredEmail;

    try {
        $sql = "SELECT count(*) FROM \"USER\" WHERE email = '$enteredEmail'";
        $stid = oci_parse($connection, $sql);
        if (!oci_execute($stid)) {
            throw new Exception("Database query failed");
        }

        if ($row = oci_fetch_assoc($stid)) {
            $count = $row['COUNT(*)'];
        }

        if ($count != 0) {
            $generatedOtp = rand(100000, 999999);
            $_SESSION['verifyOtp'] = $generatedOtp;

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'cleckshophub@gmail.com';
                $mail->Password = 'wxnc kjpg ypto rzyf';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('cleckshophub@gmail.com');
                $mail->addAddress($enteredEmail);

                $mail->isHTML(true);
                $mail->Subject = "OTP verification code.";
                $mail->Body = "This is your OTP verification code $generatedOtp";

                $mail->send();

                // Display the popup
                echo "<div id='popup' class='popup'>
                          <div class='popup-content'>
                              <form method='POST'>
                                  <p>Please enter the OTP to verify.</p>
                                  <input type='text' name='otp' placeholder='Enter OTP' required>
                                  <button type='submit' name='verifyOtp'>Submit</button>
                              </form>
                          </div>
                      </div>";
            } catch (Exception $e) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
        } else {
            $error_message = 'Email Does not Exist !!!.';
            echo "<div style='color: red;'>$error_message</div>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['verifyOtp'])) {
    $enteredOtp = $_POST['otp'];
    $otp = $_SESSION['verifyOtp'];

    if ($enteredOtp == $otp) {
        echo "<script>window.location.href = 'new_password.php';</script>";
    }
}
?>



<body id="loginBody" class="d-flex flex-column">
    <section id="loginSection">
        <div id="formContent" class="form-content">
            <div id="formIntro" class="form-intro">
                Enter Your Email To Continue !!!
            </div>

            <form id="loginForm" method="POST">
                <div id="emailInputBx" class="inputBx form-group">
                    <label for="emailLogin">Email:</label>
                    <input type="text" id="email" name="email" class="form-control" required value=<?php if (isset($_POST['email'])) {
                                                                                                        echo  $_POST['email'];
                                                                                                    }
                                                                                                    ?>>
                </div>
        </div>
        <div id="submitInputBx" class="inputBx form-group">
            <input type="submit" value="Next" name="validateEmail" class="btn btn-primary">
        </div>
        </form>

    </section>


    <script>
        //-----------popup for OTP verification---------------//
        // JavaScript to display the popup
        document.addEventListener('DOMContentLoaded', function() {
            // Show the popup
            document.getElementById('popup').style.display = 'block';
        });
    </script>
</body>

</html>