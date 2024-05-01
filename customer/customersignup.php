<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Customer Signup - CleckShopHub</title>
    <link rel="stylesheet" href="../css/signup.css">
</head>

<body>

    <?php
    include("../connection.php");
    include '../inc/header1.php'; ?>

    <div class="container">
        <div class="form-container">
            <h2>Welcome to CleckShopHub,<br> Buy with Us</h2>
            <p>Want to become a seller? <a href="../trader/tradersignup.php">Click here</a></p>
            <form method="post">
                <div class="inline-fields">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="contact_number" placeholder="Contact Number" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="username" placeholder="Username" required>
                <div class="inline-fields">
                    <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
                    <select name="gender">
                        <option value="" disabled selected>Gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                        <option value="O">Other</option>
                    </select>
                </div>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
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


<?php
if (isset($_POST['signUp'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $address = $_POST['address'];
    $contact = $_POST['contact_number'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $confirmpassword = $_POST['confirm_password'];
    $email = $_POST['email'];
    $dob = date('d/m/Y', strtotime($_POST['date_of_birth']));


    $sql = "INSERT INTO \"USER\" (User_id, Username, Password, Email, First_name, Last_name, Contact_number, Role, Created_date, Last_loggedin_date)
    VALUES (null, '$username', '$confirmpassword', '$email', '$firstName', '$lastName', '$contact', 'C', SYSDATE, null)";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);

    $sql = "select user_id from \"USER\" where username = '$username'";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $user_id = null;
    if ($row = oci_fetch_assoc($stid)) {
        $user_id = $row['USER_ID'];
    } else {
        echo "User not found";
    }
    $sql = "insert into customer values('$user_id','$address',to_date('$dob','dd/mm/yyyy'),'$gender',null,1)";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    echo "<script>window.location.href = '../login.php';</script>";
    oci_close($connection);
}


?>

</html>