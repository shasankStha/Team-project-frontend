<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/login.css">
  <title>Renew Password</title>

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
    display: flex;
    justify-content: center;
    align-items: center;
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
</style>



<body id="loginBody" class="d-flex flex-column">
  <?php
  session_start();
  include("../connection.php");
  if (isset($_POST['changePassword'])) {
    $confirmPassword = $_POST['confirmpassword'];
    $password = $_POST['password'];
    $email = $_SESSION['enteredEmail'];

    if ($password != $confirmPassword) {
      $error_message = 'Password and Confirm Password do not match !!!.';
      echo "<div style='color: red';>$error_message</div>";
    } else if (strlen($confirmPassword) < 8 || strlen($confirmPassword) > 32) {
      $error_message = "Password should be 8 to 32 character long.<br>";
      echo "<div style='color: red';>$error_message</div>";
    } else if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $_POST['password'])) {
      $error_message = "Password should contain an uppercase,<br>Number<br>and a special character<br>";
      echo "<div style='color: red';>$error_message</div>";
    } else {
      try{
        $sql = "select user_id from \"USER\" where email= '$email'";
        $stid = oci_parse($connection, $sql);
        if (!$stid) {
          $e = oci_error($connection);
          throw new Exception("Failed to parse query to fetch user ID: " . $e['message']);
        }

        oci_execute($stid);
        if(!oci_execute($stid)){
          $e = oci_error($stid);
          throw new Exception("Failed to execute query to fetch user ID: " . $e['message']);
        }

        $user_id = null;
        if ($row = oci_fetch_assoc($stid)) {
          $user_id = $row['USER_ID'];
        }
        else{
          throw new Exception("User not found");
        }

        $sql = "update \"USER\" set password = password_encrypt('$confirmPassword') where user_id = '$user_id'";
        $stid = oci_parse($connection, $sql);
        if (!$stid) {
          $e = oci_error($connection);
          throw new Exception("Failed to parse query to update password: " . $e['message']);
        }
        oci_execute($stid);
        if (!oci_execute($stid)) {
          $e = oci_error($stid);
          throw new Exception("Failed to execute query to update password: " . $e['message']);
        }

        echo "<script>window.location.href = '../login/login.php';</script>";
      }
      catch(Exception $e){
        echo "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
      }
    }
  }


  ?>
  <section id="loginSection">
    <div id="formContent" class="form-content">
      <div id="formIntro" class="form-intro">
        Enter your new password.
      </div>

      <form id="loginForm" method="POST">
        <div id="PasswordInputBx" class="inputBx form-group">
          <label for="Newpassword">New Password</label>
          <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div id="PasswordInputBx" class="inputBx form-group">
          <label for="Confirmpassword">Confirm New Password</label>
          <input type="password" id="password" name="confirmpassword" class="form-control" required>
        </div>
    </div>
    <div id="submitInputBx" class="inputBx form-group">
      <input type="submit" value="Change password" name="changePassword" class="btn btn-primary">
    </div>
    </form>
  </section>

</body>

</html>