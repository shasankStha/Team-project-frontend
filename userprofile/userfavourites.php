<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('../inc/links.php'); ?>
    <title>User profile</title>
    <link rel="stylesheet" href="../css/userfavourites.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php
    include('../connection.php');
    session_start();
    $user_id = $_SESSION['userID'];

    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
    }
    ?>

    <!-- Side Bar-->
    <div class="container">
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <a href="userprofile.php">Profile</a>
            <a href="userorderhistory.php">Orders History</a>
            <a href="userfavourites.php">Favourites</a>
            <a href="userchangepassword.php">Change Password</a>
            <a href="../logout/logout.php">Log out</a>
        </div>

        <div class="main-content">
            <div class="favourites-container grid grid-rows-1 sm:grid-rows-2 md:grid-rows-3 lg:grid-ropws-4 gap-4 gap-y-6">
                <h1>Favourites</h1>
                <div class="favourites">
                    <?php
                    $sql = "SELECT p.product_id, p.shop_id, p.name, p.price, p.image FROM favourite_item f
                            INNER JOIN product p ON f.product_id = p.product_id
                            WHERE user_id= '$user_id'";
                    $stid = oci_parse($connection, $sql);
                    oci_execute($stid);

                    while ($row = oci_fetch_assoc($stid)) {
                        $productId = htmlspecialchars($row['PRODUCT_ID']);
                        $name = htmlspecialchars($row['NAME']);
                        $price = htmlspecialchars($row['PRICE']);
                        $image = htmlspecialchars($row['IMAGE']);
                        $shopId = htmlspecialchars($row['SHOP_ID']);

                        echo "
                        <div class='card border-0 shadow product-item'>
                            <a href='../products/productspage.php?product_id=$productId&shop_id=$shopId' class='text-decoration-none text-dark'>
                                <div class='product-image'>
                                    <img src='../traderdashboard/productsImages/$image' alt='Product Image' style='width:110px;' />
                                    <div class='favorite-icon' onclick='toggleFavorite(this)' data-product-id='$productId'></div>
                                </div>
                                <div class='product-info'>
                                    <h3 class='product-name'>$name</h3>
                                    <div class='product-rating'>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star'></i>
                                        <i class='fas fa-star'></i>
                                        <i class='far fa-star'></i>
                                    </div>
                                    <div class='product-price' style='color: black;'>£ $price</div>
                                </div>
                                <button class='btn btn-success btn-add-to-cart'>Add to Cart</button>
                            </a>
                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php require('../inc/footer.php'); ?>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('expanded');
        }
    </script>
</body>

</html>
