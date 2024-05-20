<?php require ('inc/links.php') ?>
<?php require ('../connection.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Panel - Dashboard</title>
    <?php require ('inc/links.php') ?>

</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION["traderUser"])) {
        header("Location: ../login/login.php");
        exit;
    }

    $traderUser = $_SESSION["traderUser"];
    // echo "<script>alert('" . addslashes($traderUser) . "');</script>";
    
    require ('traderdashboardheader.php')
        ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>DASHBOARD</h3>

                </div>

                <!--general settings-->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5>Orders, Products and Reviews Analytics</h5>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6 style="color: navy;">Total Orders</h6>
                            <?php
                            $query = "SELECT COUNT(*) AS ORDER_ID FROM \"ORDER\"";
                            $stmp = oci_parse($connection, $query);
                            oci_execute($stmp);
                            $row = oci_fetch_array($stmp, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: navy;"><?= $row['ORDER_ID'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                    <div class="card text-center text-primary p-3">
                            <h6 style="color: green;">Total Products</h6>
                            <?php
                            $query = "SELECT COUNT(*) AS PRODUCT_ID FROM \"PRODUCT\"";
                            $stmp = oci_parse($connection, $query);
                            oci_execute($stmp);
                            $row = oci_fetch_array($stmp, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: green;"><?= $row['PRODUCT_ID'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                    <div class="card text-center text-primary p-3">
                            <h6 style="color: red;" >Pending Orders</h6>
                            <?php
                            $query = "SELECT COUNT(*) AS ORDER_ID FROM \"ORDER\"";
                            $stmp = oci_parse($connection, $query);
                            oci_execute($stmp);
                            $row = oci_fetch_array($stmp, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: red;"><?= $row['ORDER_ID'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                    <div class="card text-center text-primary p-3">
                            <h6>Reviews</h6>
                            <?php
                            $query = "SELECT COUNT(*) AS REVIEW_ID FROM \"REVIEW\"";
                            $stmp = oci_parse($connection, $query);
                            oci_execute($stmp);
                            $row = oci_fetch_array($stmp, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0"><?= $row['REVIEW_ID'] ?></h1>
                        </div>  
                    </div>

                </div>
                <div class="card" style="width: 28rem;">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <h4 class="card-subtitle mb-2 text-muted">Reports</h4>
                        <p class="card-text">View Trader Reports</p>
                        <div class="btn btn-dark">
                            <a href="http://localhost:8080/apex/f?p=103:LOGIN_DESKTOP"
                                style="text-decoration: none; color: inherit;" target="blank">CLICK HERE</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</body>


</html>