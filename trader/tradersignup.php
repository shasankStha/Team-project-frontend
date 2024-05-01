<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/signup.css">
    <title>Trader Signin - CleckShopHub</title>

</head>

<body>

    <?php
    session_start();
    include '../inc/header1.php'; ?>

    <div class="container">
        <div class="form-container">
            <h2>Welcome to CleckShopHub,<br> Sell with Us</h2>
            <p>Want to buy local products? <a href="../customer/customersignup.php">Click here</a></p>
            <br>
            <form method="post">
                <div class="inline-fields">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="contact_number" placeholder="Contact Number" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                <button type="submit" name='signUp'>Next</button>
                <br>
                <div class="center-text">
                    <p>Already have an account? <a href="../login/login.php">Login</a></p>
                </div>
            </form>
        </div>
        <div class="image-container">
            <!-- Image container content -->
        </div>
    </div>

</body>
<?php
if (isset($_POST['signUp'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact_number'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $confirmpassword = $_POST['confirm_password'];


    $_SESSION['traderSignupData'] = array(
        'firstname' => $firstName,
        'lastname' => $lastName,
        'address' => $address,
        'contact' => $contact,
        'email' => $email,
        'username' => $username,
        'password' => $confirmpassword

    );
    echo "<script>window.location.href = '../shops/shopdetails.php';</script>";
}

?>

</html>