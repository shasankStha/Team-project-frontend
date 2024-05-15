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
    <?php
    session_start();
    include("../connection.php");
    require('../inc/header1.php');
    $error = ''; // Initialize the error message variable
    if (isset($_POST['btnSignInLogin'])) {
        $username = $_POST['emailLogin'];
        $password = $_POST['passwordLogin'];

        $sql = "SELECT user_id, role FROM \"USER\" WHERE (username = :username OR email = :username) AND password = password_encrypt(:password)";
        $stid = oci_parse($connection, $sql);
        oci_bind_by_name($stid, ':username', $username);
        oci_bind_by_name($stid, ':password', $password);
        oci_execute($stid);

        $user_id = null;
        $role = null;

        // Check if a matching user is found
        if ($row = oci_fetch_assoc($stid)) {
            $user_id = $row['USER_ID'];
            $role = $row['ROLE'];
        } else {
            // Invalid login attempt
            $error = 'Invalid username/email or password.';
        }

        if ($role == "C") {
            $_SESSION["user"] = $username;
            $_SESSION["loggedinUser"] = TRUE;
            header("Location: ../index.php");
            exit;
        } elseif ($role == "T") {
            header("Location: ../traderdashboard");
            exit;
        } elseif ($role == "A") {
            header("Location: ../admin");
            exit;
        }

        oci_close($connection);
    }
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
                    <?php if (!empty($error)) : ?>
                        <div style="color: red;"><?= $error ?></div>
                    <?php endif; ?>
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

</html>