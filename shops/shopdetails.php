<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/shopdetails.css">
    <title>Shopdetails - CleckShopHub</title>


</head>
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
    //-----------popup for OTP verification---------------//
    // JavaScript to display the popup
    document.addEventListener('DOMContentLoaded', function() {
        // Show the popup
        document.getElementById('popup').style.display = 'block';
    });
</script>


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


                <input type="text" name="shop_name" placeholder="Shop Name" required value="<?php echo isset($_POST['shop_name']) ? $_POST['shop_name'] : ''; ?>">
                <input type="text" name="contact_number" placeholder="Contact Number" required value="<?php echo isset($_POST['contact_number']) ? $_POST['contact_number'] : ''; ?>">
                <input type="text" name="location" placeholder="Location" required value="<?php echo isset($_POST['location']) ? $_POST['location'] : ''; ?>">
                <textarea name="description" placeholder="Description" rows="7" required value="<?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?>"></textarea>


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

    try{
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
            echo "<script>
                    document.getElementById('popup').style.display = 'block';
                </script>";
        } else {
            $error_message = "Email cannot be sent";
        }
    }
    catch(Exception $e){
        $error_message = "Mailer Error: " . $mail->ErrorInfo;
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


        try{
            //Insert in User table
            $sql = "INSERT INTO \"USER\" (User_id, Username, Password, Email, First_name, Last_name, Contact_number, Role, Created_date, Last_loggedin_date)
            VALUES (null, '$username', '$confirmpassword', '$email', '$firstname', '$lastname', '$contact', 'T', SYSDATE, null)";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            if (!oci_execute($stid)) {
                $error = oci_error($stid);
                throw new Exception($error['message']);
            }


            //Getting user_id
            $sql = "select user_id from \"USER\" where username = '$username'";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            if (!oci_execute($stid)) {
                $error = oci_error($stid);
                throw new Exception($error['message']);
            }

            $user_id = null;
            if ($row = oci_fetch_assoc($stid)) {
                $user_id = $row['USER_ID'];
            } else {
                throw new Exception("User not found");
            }


            //Adding data in trader table
            $sql = "INSERT INTO TRADER VALUES ('$user_id', '$address', 0)";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            if (!oci_execute($stid)) {
                $error = oci_error($stid);
                throw new Exception($error['message']);
            }

            //Adding data in shop table
            $sql = "INSERT INTO SHOP VALUES (null, '$shopname','$description','$location',null,'$contactnumber', '$user_id')";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            if (!oci_execute($stid)) {
                $error = oci_error($stid);
                throw new Exception($error['message']);
            }


            //Destroying the session global variable
            unset($_SESSION['traderSignupData']);
            //Closing oracle connection
            oci_close($connection);


            echo "<script>window.location.href = '../login/login.php';</script>";
        } 
        catch(Exception $e){
            echo "<script>alert('An error occurred: " . $e->getMessage() . "');</script>";
        }
    }
    else {
        echo "OTP did not match.";
    }  
}
?>
</html>