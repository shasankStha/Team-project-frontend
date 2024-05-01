<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require ('../inc/links.php'); ?>
    <title>Order History - CleckShopHub</title>
    <link rel="stylesheet" href="../css/traderorderhistory.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Style  CSS -->
    
</head>

</style>

</head>

<body>
    <?php require ('../inc/header.php'); ?>

    <!-- Side Bar-->
    <div class="container">
        <div class="sidebar">
            <a href="traderprofile.php">Profile</a>
            <a href="orderhistory.php">Orders History</a>
            <a href="traderchangepassword.php">Change Password</a>
            <a href="../contactus/contactus.php">Contact Us</a>
            <a href="logout.php">Log out</a>
        </div>
        <div class="main-content">
            <div class="order-history">

                <h1>Order History</h1>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order Id</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>1</td>
                            <td>xxx</td>
                            <td>xxx</td>
                            <td>xxxx</td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>xxx</td>
                            <td>xxx</td>
                            <td>xxxx</td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>xxx</td>
                            <td>xxx</td>
                            <td>xxxx</td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td>xxx</td>
                            <td>xxx</td>
                            <td>xxxx</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <?php require ('../inc/footer.php'); ?>

</body>

</html>