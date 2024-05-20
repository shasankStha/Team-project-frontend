<?php
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
  oci_bind_by_name($stid, ':order_id', $order_id);
  oci_execute($stid);

  $result = [];
  while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
    $result[] = $row;
  }

  echo json_encode($result);
}
