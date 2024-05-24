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
FROM order_item oi 
inner join product p on oi.product_id = p.product_id 
WHERE oi.order_id = :order_id";

  $stid = oci_parse($connection, $query);
  oci_bind_by_name($stid, ':order_id', $order_id);
  oci_execute($stid);

  $result = [];
  while ($row = oci_fetch_array($stid)) {
    $result[] = $row;
  }

  echo json_encode($result);
}
