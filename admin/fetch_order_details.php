<?php
session_start();

if (!isset($_SESSION["admin"]) || $_SESSION['loggedinUser'] === FALSE) {
    header("Location: ../login/login.php");
    exit;
}

require('../connection.php');

if (isset($_GET['order_id'])) {
  $order_id = $_GET['order_id'];

  $query = "SELECT oi.ORDER_ITEM_ID,
                   p.name AS PRODUCT_NAME,
                   oi.QUANTITY,
                   oi.PRICE AS PRICE_PER_UNIT,
                   oi.DISCOUNT,
                   oi.TOTAL_AMOUNT
            FROM trader t
            INNER JOIN \"USER\" u ON u.user_id = t.user_id
            INNER JOIN shop s ON s.user_id = t.user_id
            INNER JOIN product p ON p.shop_id = s.shop_id
            INNER JOIN order_item oi ON oi.product_id = p.product_id
            INNER JOIN \"ORDER\" o ON o.order_id = oi.order_id
            WHERE o.order_id = :order_id";

  $stid = oci_parse($connection, $query);
  if (!$stid) {
    $e = oci_error($connection);
    echo json_encode(["error" => $e['message']]);
    exit;
  }

  oci_bind_by_name($stid, ':order_id', $order_id);

  $r = oci_execute($stid);
  if (!$r) {
    $e = oci_error($stid);
    echo json_encode(["error" => $e['message']]);
    exit;
  }

  $result = [];
  while ($row = oci_fetch_assoc($stid)) {
    $result[] = $row;
  }

  oci_free_statement($stid);
  oci_close($connection);

  echo json_encode($result);
}
