<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('../inc/links.php');
    ?>
    <title>CleckShopHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../css/homepage.css">

</head>

<body>
    <?php
    session_start();
    include('../connection.php');
    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;
    $user_id = null;
    if ($isLoggedIn) {
        $user_id = $_SESSION['userID'];
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
        $user_id = 1;
    }

    ?>


    <!-- Swiper-->
    <div class="container-fluid">
        <div class="swiper swiper-container ">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="../images/img1.jpeg">
                </div>
                <div class="swiper-slide">
                    <img src="../images/img2.jpeg">
                </div>
                <div class="swiper-slide">
                    <img src="../images/img4.jpeg">
                </div>
                <div class="swiper-slide">
                    <img src="../images/img6.jpeg">
                </div>
                <div class="swiper-slide">
                    <img src="../images/img3.jpeg">
                </div>
                <div class="swiper-slide">
                    <img src="../images/img5.jpeg">
                </div>
            </div>
            <div class="swiper-pagination"></div>

        </div>
    </div>

    <!-- For you -->
    <div class="container">
        <h2 class="mt-5 pt-4 mb-5 text-leftalign fw-bold" style="padding-left: 50px;">For you</h2>
        <div class="container">
            <div class="swiper card-swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    $sql = "select * from (select p.product_id,p.name,p.price,p.image,p.shop_id FROM product p ORDER BY DBMS_RANDOM.VALUE) where ROWNUM <= 8 ";
                    $stid = oci_parse($connection, $sql);
                    oci_execute($stid);

                    while ($row = oci_fetch_assoc($stid)) {
                        $productId = htmlspecialchars($row['PRODUCT_ID']);
                        $name = htmlspecialchars($row['NAME']);
                        $price = htmlspecialchars($row['PRICE']);
                        $image = htmlspecialchars($row['IMAGE']);
                        $shopId = htmlspecialchars($row['SHOP_ID']);

                        echo "
                            <div class='swiper-slide'>
                                <div class='card border-0 shadow product-item'>
                                    <a href='../products/productspage.php?product_id=$productId&shop_id=$shopId' class='text-decoration-none text-dark'>
                                    <div class='product-image'>
                                        <img src='../traderdashboard/productsImages/$image' alt='Product Image' style='width:110px;' />
                                        <div class='favorite-icon' onclick='toggleFavorite(this)' data-product-id='$productId'>
                                            <i class='far fa-heart'></i>
                                        </div>
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
                                            <div class='product-price'>£ $price</div>
                                    </div>
                                    </a>
                                    <button class='btn btn-success btn-add-to-cart'>Add to Cart</button>
                                </div>
                            </div>";
                    }
                    ?>
                    <div class="swiper-pagination"></div>
                </div>

            </div>
            <div class="button-container">
                <a href="../products/products.php" class="btn btn-primary mt-4">More Products</a>
            </div>
        </div>
        <br><br><br>


        <!-- Trending items -->
        <div class="container">
            <h2 class="mt-5 pt-4 mb-5 text-leftalign fw-bold" style="padding-left: 50px;">Trending Items</h2>
            <div class="swiper card-swiper-container">
                <div class="swiper-wrapper">
                    <?php
                    // Fetch products for the shop
                    $sql = "SELECT p.Product_id,
                    p.Name ,
                    p.Price,
                    p.Image,
                    p.Shop_id,
                    SUM(oi.Quantity) AS Total_Sold
             FROM ORDER_ITEM oi
             JOIN PRODUCT p ON oi.Product_id = p.Product_id
             GROUP BY p.Product_id, p.Name, p.Price, p.Image, p.Shop_id
             ORDER BY SUM(oi.Quantity) DESC";


                    $stid = oci_parse($connection, $sql);
                    oci_execute($stid);

                    while ($row = oci_fetch_assoc($stid)) {
                        $productId = htmlspecialchars($row['PRODUCT_ID']);
                        $name = htmlspecialchars($row['NAME']);
                        $price = htmlspecialchars($row['PRICE']);
                        $image = htmlspecialchars($row['IMAGE']);
                        $shopId = htmlspecialchars($row['SHOP_ID']); // Assuming shop_id is part of the product table

                        echo "
        <div class='swiper-slide'>
            <div class='card border-0 shadow product-item'>
                <a href='../products/productspage.php?product_id=$productId&shop_id=$shopId' class='text-decoration-none text-dark'>
                    <div class='product-image'>
                        <img src='../traderdashboard/productsImages/$image' alt='Product Image' style='width:110px;' />
                        <div class='favorite-icon' onclick='toggleFavorite(this)' data-product-id='$productId'>
                            <i class='far fa-heart'></i>
                        </div>
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
                        <div class='product-price'>£ $price</div>
                    </div>
                </a>
                <button class='btn btn-success btn-add-to-cart'>Add to Cart</button>
            </div>
        </div>";
                    }
                    ?>
                    <!-- End of product block -->
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="button-container">
                <a href="../products/products.php" class="btn btn-primary mt-4">More Products</a>
            </div>
        </div>
    </div>
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold ">REACH US</h2>

    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-gray rounded">

                <iframe class="w-100 rounded" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.817147278988!2d85.31712757608553!3d27.692045826165188!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb190022762715%3A0xbd8f893a64dc355a!2sCleckShopHub!5e0!3m2!1sen!2snp!4v1711702498567!5m2!1sen!2snp" loading="lazy"></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="bg-gray p-4 rounded mb-4">
                    <h5>Call us</h5>

                    <a href="tel:+1234567890" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +1234567890
                    </a>
                    <br>

                    <a href="tel:+0987654321" class="d-inline-block text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +977 9746312195
                    </a>
                </div>
                <div class="bg-gray p-4 rounded mb-4">
                    <h5>Follow us</h5>

                    <a href="https://twitter.com/yourusername" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-twitter me-1"></i> Twitter
                        </span>
                    </a>
                    <br>
                    <a href="https://facebook.com/yourusername" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-facebook me-1"></i> Facebook
                        </span>
                    </a>
                    <br>
                    <a href="https://instagram.com/yourusername" class="d-inline-block">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-instagram me-1"></i> Instagram
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <br><br>




    <?php require('../inc/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".swiper-container", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: true,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true,
            },
            pagination: {
                el: ".swiper-pagination",
            },
            autoplay: {
                delay: 5000, // Increased delay for smoother transition
                disableOnInteraction: false, // Autoplay does not stop after interactions
                speed: 1000 // Transition speed in milliseconds
            },

        });
        var cardSwiper = new Swiper(".card-swiper-container", {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2.5,
                    spaceBetween: 25,
                },
                768: {
                    slidesPerView: 3.5,
                    spaceBetween: 35,
                },
                1024: {
                    slidesPerView: 4.5,
                    spaceBetween: 45,
                },
            }
        });

        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 30,
            effect: "fade",
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                speed: 1000
            },
            slidesPerView: "auto",
            loop: true,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true,
            },
            disableOnInteraction: false,
        });
        document.querySelectorAll('.btn-add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                alert('Added to cart!');
                // Implement additional functionality here, such as updating cart count
            });
        });
    </script>
</body>

</html>