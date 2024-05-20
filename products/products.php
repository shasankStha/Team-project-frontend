<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Results - CleckShopHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/products.css">

</head>

<body class="overflow-x-hidden">
    <?php
    include('../connection.php');
    session_start();
    $search = $_SESSION['search'] || $_GET['search'];
    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
    }
    ?>


    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar-box w-1/4 px-0">
            <div class="px-4 mt-2 flex gap-6">
                <div class="w-3/4 px-0">
                    <div class="mt-3">
                        <h1>Categories</h1>
                        <div class="mt-3">
                            <?php
                            $sql = "select * from (select * from product_category ORDER BY DBMS_RANDOM.VALUE) where ROWNUM <=8";
                            $stid = oci_parse($connection, $sql);
                            oci_execute($stid);
                            while ($row = oci_fetch_assoc($stid)) {
                                $category_id = htmlspecialchars($row['CATEGORY_ID']);
                                $name = htmlspecialchars($row['CATEGORY_NAME']);
                                echo "<p class=\"text-gray-500 mt-2\"><a href=\"../products/products.php?search=", urlencode($name), "\" class=\"no-underline text-white\">$name</a></p>";
                            }

                            ?>
                        </div>
                    </div>
                    <br>
                    <div class="mt-3">
                        <h1>Price Range</h1>
                        <div class="mt-3">
                            <div class="flex justify-between mt-1">
                                <div>
                                    <p class="text-gray-500">£0.00 - £50</p>
                                </div>
                                <div class="w-[50px] h-[18px]">
                                    <input type="checkbox" class="w-full h-full text-xs" />
                                </div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <div>
                                    <p class="text-gray-500">£50 - £100</p>
                                </div>
                                <div class="w-[50px] h-[18px]">
                                    <input type="checkbox" class="w-full h-full text-xs" />
                                </div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <div>
                                    <p class="text-gray-500">£100 - £150</p>
                                </div>
                                <div class="w-[50px] h-[18px]">
                                    <input type="checkbox" class="w-full h-full text-xs" />
                                </div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <div>
                                    <p class="text-gray-500">£150 - £200</p>
                                </div>
                                <div class="w-[50px] h-[18px]">
                                    <input type="checkbox" class="w-full h-full text-xs" />
                                </div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <div>
                                    <p class="text-gray-500">£200+</p>
                                </div>
                                <div class="w-[50px] h-[18px]">
                                    <input type="checkbox" class="w-full h-full text-xs" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="w-3/4">
            <div class="flex justify-between">
                <h2 class="text-xl font-bold my-4">All Products</h2>
            </div>
            <div class=" grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 gap-y-6">

                <?php
                $sql = "select p.product_id,p.name,p.price,p.image,p.shop_id,
                case 
                    when p.name like '%' || '$search' || '%' then 3
                    when c.category_name like '%' || '$search' || '%' then 2
                    when s.shop_name like '%' || '$search' || '%' then 1
                    else 0
                end as relevance_score
                from 
                product p
                inner join product_category c on p.category_id = c.category_id
                inner join shop s on p.shop_id = s.shop_id
                where (p.name like '%' || '$search' || '%' or c.category_name like '%' || '$search' || '%' or s.shop_name like '%' || '$search' || '%')
                order by relevance_score desc, p.product_id";
                $stid = oci_parse($connection, $sql);
                oci_execute($stid);
                while ($row = oci_fetch_assoc($stid)) {
                    $productId = htmlspecialchars($row['PRODUCT_ID']);
                    $name = htmlspecialchars($row['NAME']);
                    $price = htmlspecialchars($row['PRICE']);
                    $image = htmlspecialchars($row['IMAGE']);
                    $shopId = htmlspecialchars($row['SHOP_ID']);

                    echo "<div>
                    <div class=\"card border-0 shadow product-item\">
                        <a href=\"../products/productspage.php?product_id=$productId&shop_id=$shopId\" class=\"text-decoration-none text-dark\">

                            <div class=\"product-image\">
                                <img src=\"../traderdashboard/productsImages/$image\" class=\"card-img-top\" alt=\"Product Image\">
                            </div>
                            <div class=\"product-info\">
                                <h3 class=\"product-name\">$name</h3>
                                <div class=\"product-rating\">
                                    <i class=\"fas fa-star\"></i>
                                    <i class=\"fas fa-star\"></i>
                                    <i class=\"fas fa-star\"></i>
                                    <i class=\"fas fa-star\"></i>
                                    <i class=\"far fa-star\"></i>
                                </div>
                                <div class=\"product-price\">£ $price</div>

                            </div>
                        </a>
                        <button class=\"btn btn-success btn-add-to-cart\">Add to Cart</button>
                    </div>

                </div>";
                }
                ?>
                <!-- <div>
                    <div class="card border-0 shadow product-item">
                        <a href="../products/productspage.php" class="text-decoration-none text-dark">

                            <div class="product-image">
                                <img src="../images/img_slider.jpg" class="card-img-top" alt="Product Name">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">Product Name</h3>
                                <div class="product-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <div class="product-price">20$</div>

                            </div>
                        </a>
                        <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                    </div>

                </div>

                <div class="card border-0 shadow product-item">
                    <a href="../products/productspage.php" class="text-decoration-none text-dark">

                        <div class="product-image">
                            <img src="../images/img_slider.jpg" class="card-img-top" alt="Product Name">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Product Name</h3>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <div class="product-price">20$</div>

                        </div>
                    </a>
                    <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                </div>
                <div class="card border-0 shadow product-item">
                    <a href="../products/productspage.php" class="text-decoration-none text-dark">

                        <div class="product-image">
                            <img src="../images/img_slider.jpg" class="card-img-top" alt="Product Name">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Product Name</h3>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <div class="product-price">20$</div>

                        </div>
                    </a>
                    <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                </div>
                <div class="card border-0 shadow product-item">
                    <a href="../products/productspage.php" class="text-decoration-none text-dark">

                        <div class="product-image">
                            <img src="../images/img_slider.jpg" class="card-img-top" alt="Product Name">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Product Name</h3>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <div class="product-price">20$</div>

                        </div>
                    </a>
                    <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                </div>
                <div class="card border-0 shadow product-item">
                    <a href="../products/productspage.php" class="text-decoration-none text-dark">

                        <div class="product-image">
                            <img src="../images/img_slider.jpg" class="card-img-top" alt="Product Name">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Product Name</h3>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <div class="product-price">20$</div>

                        </div>
                    </a>
                    <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                </div>
                <div class="card border-0 shadow product-item">
                    <a href="../products/productspage.php" class="text-decoration-none text-dark">

                        <div class="product-image">
                            <img src="../images/img_slider.jpg" class="card-img-top" alt="Product Name">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Product Name</h3>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <div class="product-price">20$</div>

                        </div>
                    </a>
                    <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                </div>
                <div class="card border-0 shadow product-item">
                    <a href="../products/productspage.php" class="text-decoration-none text-dark">

                        <div class="product-image">
                            <img src="../images/img_slider.jpg" class="card-img-top" alt="Product Name">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Product Name</h3>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <div class="product-price">20$</div>

                        </div>
                    </a>
                    <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                </div>
                <div class="card border-0 shadow product-item">
                    <a href="../products/productspage.php" class="text-decoration-none text-dark">

                        <div class="product-image">
                            <img src="../images/img_slider.jpg" class="card-img-top" alt="Product Name">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Product Name</h3>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <div class="product-price">20$</div>

                        </div>
                    </a>
                    <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                </div> -->
            </div>
        </div>
    </div>


    <?php require('../inc/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="../js/carousel.js"></script>
</body>

</html>