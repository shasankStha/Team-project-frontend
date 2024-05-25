<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard -CleckShopHub</title>

    <?php 
    
    if (!isset($_SESSION['traderUser']) || $_SESSION['loggedinTrader'] !== TRUE) {
        header('Location: ../login/login.php');
        exit;
    }
    
    require ('inc/links.php') ?>
</head>
<style>
    :root {
        --teal: #2ec1ac;
        --teal_hover: #279e8c;
    }

    * {
        font-family: 'Poppins', sans-serif;
    }

    .h-font {
        font-family: 'Ariel', sans-serif;
    }

    #dashboard-menu {
        position: fixed;
        height: 100%;
        z-index: 11;
    }

    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: rgb(36, 36, 36);
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    @media screen and (max-width: 991px) {
        #dashboard-menu {
            height: auto;
            width: 100%;
        }

        #main-content {
            margin-top: 60px;
        }
    }
</style>

<body>
    <div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top">
        <h3 class="mb-0 h-font">CLECKSHOPHUB</h3>
        <a href="../logout/logout.php" class="btn btn-light btn-sm">LOG OUT</a>
    </div>

    <div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid flex-lg-column align-items-stretch">
                <h4 class="mt-2 text-light">TRADER PANEL</h4>
                <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="products.php">Products</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="orders.php">Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="settings.php">Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>


</html>