<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <title>Login - CleckShopHub</title>
</head>

<body>
    <?php
    session_start();
    include("../connection.php");
    require('../inc/header1.php');
    echo "<br><br>";

    $savedUsername = isset($_COOKIE['rememberedUser']) ? $_COOKIE['rememberedUser'] : '';
    $error = ''; // Initialize the error message variable
    if (isset($_POST['btnSignInLogin'])) {
        $username = $_POST['emailLogin'];
        $password = $_POST['passwordLogin'];
        $rememberMe = isset($_POST['rememberMeLogin']);

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

        if ($user_id) {
            // Set or clear the cookie based on the "Remember Me" checkbox
            if ($rememberMe) {
                setcookie('rememberedUser', $username, time() + (86400 * 30), "/"); // Set cookie for 30 days
            } else {
                setcookie('rememberedUser', '', time() - 3600, "/"); // Clear the cookie
            }

            if ($role == "C") {
                $sql = "SELECT status FROM customer WHERE user_id = $user_id";
                $stid = oci_parse($connection, $sql);
                oci_execute($stid);
                $status = null;
                if ($row = oci_fetch_assoc($stid)) {
                    $status = $row['STATUS'];
                } else {
                    echo "<script>alert('Something went wrong!')</script>";
                }
                if ($status == '1') {
                    $_SESSION["user"] = $username;
                    $_SESSION['userID'] = $user_id;
                    $_SESSION["loggedinUser"] = TRUE;
                    $sql = "update \"USER\" set LAST_LOGGEDIN_DATE = sysdate where user_id = '$user_id'";
                    $stid = oci_parse($connection, $sql);
                    $exe = oci_execute($stid);
                    if ($exe) {
                        header("Location: ../index.php");
                        exit;
                    } else {
                        echo "<script>alert('Error logging in.')</script>";
                    }
                } else {
                    echo "<script>alert('Your account has been removed!')</script>";
                }
            } elseif ($role == "T") {
                $_SESSION["traderUser"] = $username;
                $_SESSION["loggedinTrader"] = TRUE;
                $_SESSION['traderID'] = $user_id;
                $sql = "select shop_id from shop where user_id = '$user_id'";
                $stid = oci_parse($connection, $sql);
                $exe = oci_execute($stid);
                $shop_id = null;
                if ($row = oci_fetch_assoc($stid)) {
                    $shop_id = $row["SHOP_ID"];
                }
                $_SESSION['shopID'] = $shop_id;
                $sql = "update \"USER\" set LAST_LOGGEDIN_DATE = sysdate where user_id = '$user_id'";
                $stid = oci_parse($connection, $sql);
                $exe = oci_execute($stid);
                if ($exe) {
                    header("Location: ../traderdashboard");
                    exit();
                } else {
                    echo "<script>alert('Error logging in.')</script>";
                }
            } elseif ($role == "A") {
                $_SESSION["admin"] = $username;
                $_SESSION["admin_id"] = $user_id;
                $_SESSION["loggedinUser"] = TRUE;
                $sql = "update \"USER\" set LAST_LOGGEDIN_DATE = sysdate where user_id = '$user_id'";
                $stid = oci_parse($connection, $sql);
                $exe = oci_execute($stid);
                if ($exe) {
                    header("Location: ../admin");
                    exit();
                } else {
                    echo "<script>alert('Error logging in.')</script>";
                }
            }
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
                    <input type="text" id="emailLogin" name="emailLogin" value="<?php echo htmlspecialchars($savedUsername); ?>" required>
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