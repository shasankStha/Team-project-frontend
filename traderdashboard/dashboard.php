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

    $traderUser = $_SESSION["traderUser"];
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
                    <h5>Orders, Reviews Analytics</h5>
                    <!-- <select class="form-select shadow-none bg-light w-auto" onchange="user_analytics(this.value)">
                        <option value="1">Past 30 Days</option>
                        <option value="2">Past 90 Days</option>
                        <option value="3">Past 1 Year</option>
                        <option value="4">All time</option>
                    </select> -->
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Total Orders</h6>
                            <h1 class="mt-2 mb-0" id="">0</h1>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Reviews</h6>
                            <h1 class="mt-2 mb-0" id="">0</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>


</html>