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

  $sql = "select p.product_id, p.name, p.image, p.price, ci.quantity, p.max_order, ci.cart_item_id
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
    $price = $row['PRICE'];
    $quantity = $row['QUANTITY'];
    $sub_total = $price * $quantity;
    $total += $sub_total;
    $cart_item_id = $row['CART_ITEM_ID'];
    $sql = "insert into order_item values(null,'$quantity','$price',null,'$sub_total','$id','$product_id')";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $sql = "delete from cart_item where CART_ITEM_ID = '$cart_item_id'";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
  }

  $sql = "update \"ORDER\" set TOTAL_PRICE = '$total', PAYMENT_CONFIRMATION = '1' where order_id = '$id'";
  $stid = oci_parse($connection, $sql);
  oci_execute($stid);
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