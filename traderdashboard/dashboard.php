<?php require('inc/links.php') ?>
<?php require('../connection.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Panel - Dashboard</title>
    <?php require('inc/links.php') ?>

</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION["traderUser"])) {
        header("Location: ../login/login.php");
        exit;
    }
    $user_id = $_SESSION['traderID'];
    $username = $_SESSION["traderUser"];
    // echo "<script>alert('" . addslashes($traderUser) . "');</script>";

    require('traderdashboardheader.php')
    ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>DASHBOARD</h3>

                </div>

                <!--general settings-->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5>Products,Orders, Payment and Reviews Analytics</h5>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6 style="color: green;">Total Products</h6>
                            <?php
                            $query = "select count(*) from product p
                            inner join shop s on p.shop_id = s.shop_id
                            where s.user_id = $user_id";
                            $stmp = oci_parse($connection, $query);
                            oci_execute($stmp);
                            $row = oci_fetch_array($stmp, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: green;"><?= $row['COUNT(*)'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6 style="color: navy;">Total Orders</h6>
                            <?php
                            $query = "select count (*) from (SELECT DISTINCT
                            o.ORDER_ID, 
                            o.ORDER_DATE, 
                            SUM(oi.TOTAL_AMOUNT) as TOTAL_PRICE 
                        FROM 
                            trader t
                            INNER JOIN \"USER\" u ON u.user_id = t.user_id
                            INNER JOIN shop s ON s.user_id = t.user_id
                            INNER JOIN product p ON p.shop_id = s.shop_id
                            INNER JOIN order_item oi ON oi.product_id = p.product_id
                            INNER JOIN \"ORDER\" o ON o.order_id = oi.order_id
                        WHERE  t.user_id = $user_id
                        GROUP BY 
                            o.ORDER_ID, 
                            o.ORDER_DATE
                        ORDER BY 
                            o.ORDER_ID DESC)";
                            $stmp = oci_parse($connection, $query);
                            oci_execute($stmp);
                            $row = oci_fetch_array($stmp, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: navy;"><?= $row['COUNT(*)'] ?></h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6 style="color: red;">Pending Orders</h6>
                            <?php
                            $query = "select count(*)
                            from trader t
                          INNER JOIN shop s ON s.user_id = t.user_id
                          INNER JOIN product p ON p.shop_id = s.shop_id
                          INNER JOIN order_item oi on oi.product_id = p.product_id
                          INNER JOIN \"ORDER\" o on o.order_id=oi.order_id
                          inner join collection_slot c on c.collection_slot_id = o.collection_slot_id
                          WHERE t.user_id = $user_id
                          and c.slot_date>sysdate";
                            $stmp = oci_parse($connection, $query);
                            oci_execute($stmp);
                            $row = oci_fetch_array($stmp, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: red;"><?= $row['COUNT(*)'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Reviews</h6>
                            <?php
                            $query = "select count(*) from
                            trader t
                            inner join shop s on s.user_id =t.user_id
                            inner join product p on s.shop_id =p.shop_id
                            inner join review r on r.product_id =p.product_id
                            where t.user_id = $user_id";
                            $stmp = oci_parse($connection, $query);
                            oci_execute($stmp);
                            $row = oci_fetch_array($stmp, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0"><?= $row['COUNT(*)'] ?></h1>
                        </div>
                    </div>

                </div>
                <div class="card" style="width: 28rem;">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <h4 class="card-subtitle mb-2 text-muted">Reports</h4>
                        <p class="card-text">View Trader Reports</p>
                        <div class="btn btn-dark">
                            <a href="http://localhost:8080/apex/f?p=103:LOGIN_DESKTOP" style="text-decoration: none; color: inherit;" target="blank">CLICK HERE</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</body>


</html>