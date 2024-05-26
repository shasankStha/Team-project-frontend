<?php
session_start();

if (!isset($_SESSION["admin"]) || $_SESSION['loggedinUser'] === FALSE) {
    header("Location: ../login/login.php");
    exit;
}
require('inc/links.php') ?>

<?php require('../connection.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
</head>

<body>
    <?php require('admindashboardheader.php') ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3>DASHBOARD</h3>
                <h5>Users, Queries, Reports Analytics</h5>

                <div class="row mb-3">
                    <!-- Total Users -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Total Users</h6>
                            <?php
                            $query = "select count(*) as total_users from \"USER\" u left join customer c on c.user_id = u.user_id where u.role = 'T' or (u.role = 'C' and c.status = '1')";
                            $stmp = oci_parse($connection, $query);
                            oci_execute($stmp);
                            $row = oci_fetch_array($stmp, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0"><?= $row['TOTAL_USERS'] ?></h1>
                        </div>
                    </div>
                    <!-- Queries -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6 style="color: green;">Messages</h6>
                            <?php
                            $query = "SELECT COUNT(*) AS total_queries FROM CONTACT_US";
                            $stmt = oci_parse($connection, $query);
                            oci_execute($stmt);
                            $row = oci_fetch_array($stmt, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: green;"><?= $row['TOTAL_QUERIES'] ?></h1>
                        </div>
                    </div>
                    <!-- Pending Requests-->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6 style="color: red;">Pending traders</h6>
                            <?php
                            $query = "SELECT COUNT(*) AS total_queries FROM TRADER WHERE status=0";
                            $stmt = oci_parse($connection, $query);
                            oci_execute($stmt);
                            $row = oci_fetch_array($stmt, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: red;"><?= $row['TOTAL_QUERIES'] ?></h1>
                        </div>
                    </div>

                    <!-- Total Traders-->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3">
                            <h6 style="color: purple;">Approved Traders</h6>
                            <?php
                            $query = "SELECT COUNT(*) AS total_queries FROM TRADER WHERE status=1";
                            $stmt = oci_parse($connection, $query);
                            oci_execute($stmt);
                            $row = oci_fetch_array($stmt, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: purple;"><?= $row['TOTAL_QUERIES'] ?></h1>

                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-center p-3">
                            <h6 style="color: navy;">Total Customers</h6>
                            <?php
                            $query = "SELECT COUNT(*) AS total_customer FROM CUSTOMER where status = '1'";
                            $stmt = oci_parse($connection, $query);
                            oci_execute($stmt);
                            $row = oci_fetch_array($stmt, OCI_ASSOC);
                            ?>
                            <h1 class="mt-2 mb-0" style="color: navy;"><?= $row['TOTAL_CUSTOMER'] ?></h1>

                        </div>
                    </div>



                </div>

                <div class="card" style="width: 28rem;">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <h4 class="card-subtitle mb-2 text-muted">Reports</h4>
                        <p class="card-text">View Reports</p>
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