<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Customer Login - CleckShopHub</title>

</head>

<body style="display: flex; flex-direction: column;">
    <?php include("../connection.php");
    require('../inc/header1.php');
    ?>
    <section>
        <div class="form-image"></div>
        <div class="form-content">
            <div class="form-intro">
                Welcome to CleckShopHub, <br>Sign In to Continue.
            </div>
            <div class="form-introDesc">
                Don't have an account? <a href="../customer/customersignup.php">Create an account</a>

            </div>
            <form action="login.php" method="POST">
                <div class="inputBx">
                    <label for="emailLogin">Email/Username</label>
                    <input type="text" id="emailLogin" name="emailLogin" required>
                </div>
                <div class="inputBx">
                    <label for="passwordLogin">Password</label>
                    <input type="password" id="passwordLogin" name="passwordLogin" required>
                </div>
                <div class="inputBx">
                    <label for="rememberMeLogin">
                        <input type="checkbox" id="rememberMeLogin" name="rememberMeLogin">
                        Remember me
                    </label>
                </div>
                <div class="inputBx">
                    <input type="submit" value="Sign In" name="btnSignInLogin">
                </div>
                <a href="forgot_password_email_page.php" class="forgot">Forgot Password?</a>
            </form>
        </div>
    </section>
</body>


<?php
if (isset($_POST['btnSignInLogin'])) {
    $username = $_POST['emailLogin'];
    $password = $_POST['passwordLogin'];


    $sql = "SELECT user_id, role FROM \"USER\" WHERE (username = '$username' OR email = '$username') AND password = password_encrypt('$password')";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);

    $user_id = null;
    $role = null;

    // Check if a matching user is found
    if ($row = oci_fetch_assoc($stid)) {
        $user_id = $row['USER_ID'];
        $role = $row['ROLE'];
    } else {
        // Invalid login attempt
        echo "<script>alert('Invalid username/email or password');</script>";
    }


    if ($role == "C")
        echo "<script>window.location.href = '../index.php';</script>";
    elseif ($role == "T")
        // Add trader dashboard
        echo "<script>window.location.href = '../index.php';</script>";
    elseif ($role == "A")
        // Add admin dashboard
        echo "<script>window.location.href = '../index.php';</script>";

    oci_close($connection);
}
?>

</html>