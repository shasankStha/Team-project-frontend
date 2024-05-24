<?php
session_start();
$isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;
$user_id = null;
if ($isLoggedIn) {
  $user_id = $_SESSION['userID'];
  require('loggedin_header.php');
} else {
  require('inc/header.php');
}
require('../inc/links.php');
include('../connection.php');


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You For Your Order!</title>
  <style>
    body {
      font-family: sans-serif;
      text-align: center;
    }

    h1 {
      font-size: 30px;
      margin-top: 1px;
    }

    h3 {
      padding-top: 1px;
    }

    p {
      font-size: 1em;
      line-height: 1.5em;
      margin-left: -30px;
    }

    .container {
      border: 3px solid black;
      align-items: center;
      width: 700px;
      height: 355px;
      margin: 50px auto;
      margin-top: 10em;
    }

    .box {
      align-items: center;
      background-color: white;
      width: 500px;
      height: 150px;
      border: 1px solid black;
      padding: 30px 50px;
      margin: 20px auto;
      padding-bottom: 10px;
    }

    .button {
      margin-top: 4em;
    }

    .button a {
      display: inline-block;
      background-color: #4caf50;
      color: white;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      font-size: 15px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      margin-top: -50px;
    }

    .button a:hover {
      background-color: rgb(17, 140, 17);
    }

    .order_info {
      text-align: left;
      margin-left: -10px;
    }

    .order_info hr {
      margin-left: -40px;
      /* Adjust the negative margin to center the line */
      border: 1px solid rgb(149, 147, 147);
      width: 32em;
    }
  </style>
</head>

<body>
  <?php
  $sql = "select max(order_id) from \"ORDER\"";
  $stid = oci_parse($connection, $sql);
  oci_execute($stid);
  $id = null;
  if ($r = oci_fetch_assoc($stid)) {
    $id = $r["MAX(ORDER_ID)"];
  }

  $sql = "select p.product_id, p.name, p.image, p.price, ci.quantity, p.max_order, ci.cart_item_id, p.discount
from cart c
inner join cart_item ci on ci.cart_id = c.cart_id
inner join product p on p.product_id = ci.product_id
where c.user_id = '$user_id'";

  $stmt = oci_parse($connection, $sql);
  oci_execute($stmt, OCI_DEFAULT);
  $name = null;
  $product_id = null;
  $price = null;
  $quantity = null;
  $cart_item_id = null;
  $total = 0;
  while ($row = oci_fetch_assoc($stmt)) {
    $name = $row['NAME'];
    $product_id = $row['PRODUCT_ID'];
    $amount = $row['PRICE'];
    $quantity = $row['QUANTITY'];
    $discount = $row['DISCOUNT'];
    $sub = $amount * $quantity;
    $dis_amt = ($sub * $discount) / 100;
    $sub_total = $sub - $dis_amt;
    $total += $sub_total;
    $cart_item_id = $row['CART_ITEM_ID'];
    $sql = "insert into order_item values(null,'$quantity','$amount','$dis_amt','$sub_total','$id','$product_id')";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $sql = "delete from cart_item where CART_ITEM_ID = '$cart_item_id'";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
  }

  $sql = "update \"ORDER\" set TOTAL_PRICE = '$total', PAYMENT_CONFIRMATION = '1' where order_id = '$id'";
  $stid = oci_parse($connection, $sql);
  oci_execute($stid);
  $sql = "insert into payment values(null,'$total',sysdate,'$user_id','$id',1)";
  $stid = oci_parse($connection, $sql);
  oci_execute($stid);

  ?>

  <?php
  $email = null;
  $name = null;
  $sql = "SELECT first_name || ' '|| last_name as name, EMAIL FROM \"USER\" WHERE USER_ID = $user_id";
  $stid = oci_parse($connection, $sql);
  oci_execute($stid);
  if ($row = oci_fetch_assoc($stid)) {
    $email = $row['EMAIL'];
    $name = $row['NAME'];
  }

  $sql = "SELECT c.slot_date, c.start_time,c.end_time, c.day_of_week, o.order_date,o.total_price FROM collection_slot c inner join \"ORDER\" o on o.COLLECTION_SLOT_ID = c.COLLECTION_SLOT_ID  WHERE o.USER_ID = $user_id and o.order_id = $id";
  $stid = oci_parse($connection, $sql);
  oci_execute($stid);
  $slot_date = $start_time = $end_time = $day = $order_date = $price = null;
  if ($row = oci_fetch_assoc($stid)) {
    $slot_date = $row['SLOT_DATE'];
    $start_time = $row['START_TIME'];
    $end_time = $row['END_TIME'];
    $day = $row['DAY_OF_WEEK'];
    $order_date = $row['ORDER_DATE'];
    $price = $row['TOTAL_PRICE'];
  }

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'phpmailer/src/Exception.php';
  require 'phpmailer/src/PHPMailer.php';
  require 'phpmailer/src/SMTP.php';

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
  $mail->Subject = "Order confirmation - Order ID : $id";
  $message = "Dear $name,

             Thank you for your purchase from CleckShopHub. We are pleased to confirm your order.

             Order Details:
            ------------------------
            Order ID: $id
            Total Amount: $price
            Order Date: $order_date

            Collection Time:
            Collection Date: $slot_date
            Time: $start_time - $end_time
            Day: $day


            Thank you for shopping with us!
            
            Best regards,
            CleckShopHub
            cleckshophub@gmail.com
            9805187622 
            ";
  $mail->Body = $message;

  $mail->send();



  ?>

  <div class="container">
    <div class="box">
      <h1>Thank You For Your Order!</h1>
      <h3>Please check your email for confirmation.</h3>
      <br>
      <!-- Order information -->
      <div class="order_info">
        <p>Order Number: <?php echo $id; ?></p>
        <hr>
        <p>Total Amount: £<?php echo $total; ?></p>
      </div>
      <div class="button">
        <a href="../homepage/homepage.php">Continue Shopping</a>
      </div>
    </div>
  </div>
</body>



</html>