<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard -CleckShopHub</title>
    
    <?php 
    // session_start();

    if (!isset($_SESSION["admin"]) || $_SESSION['loggedinUser'] === FALSE) {
        header("Location: ../login/login.php");
        exit;
    }
    
    require('inc/links.php') ?>

</head>

<body>
    <div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top">
        <h3 class="mb-0 h-font">CLECKSHOPHUB</h3>
        <a href="../logout/logout.php" class="btn btn-light btn-sm">LOG OUT</a>
    </div>

    <div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid flex-lg-column align-items-stretch">
                <h4 class="mt-2 text-light">ADMIN PANEL</h4>
                <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="approval.php">Trader Approval</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="users.php">Customers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="orders.php">Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="userqueries.php">Contact Us Messages</a>
                        </li>
                        



                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>


</html>