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
    $search = isset($_SESSION['search']) ? $_SESSION['search'] : ($_GET['search'] ?? "");

    $categoryFilter = $_GET['category'] ?? null;
    $priceFilter = $_GET['price'] ?? null;
    $sortOrder = $_GET['sort'] ?? null;
    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
    }
    ?>

    <div class="flex flex-wrap md:flex-nowrap">
        <!-- Sidebar -->
        <div class="sidebar-box w-full md:w-1/4 px-0">
            <div class="px-4 mt-2 flex gap-6">
                <div class="w-full px-0">
                    <div class="mt-3">
                        <h1>Categories</h1>
                        <div class="mt-3">
                            <?php
                            $sql = "select * from (select * from product_category ORDER BY DBMS_RANDOM.VALUE) where ROWNUM <= 8";
                            $stid = oci_parse($connection, $sql);
                            oci_execute($stid);
                            while ($row = oci_fetch_assoc($stid)) {
                                $category_id = htmlspecialchars($row['CATEGORY_ID']);
                                $name = htmlspecialchars($row['CATEGORY_NAME']);
                                echo "<p class=\"text-gray-500 mt-2\"><a href=\"products.php?category=", urlencode($name), "\" class=\"no-underline text-white\">$name</a></p>";
                            }
                            ?>
                        </div>
                    </div>
                    <br>
                    <div class="mt-3">
                        <h1>Price Range</h1>
                        <div class="mt-3">
                            <?php
                            $priceRanges = [
                                '0-50' => '£0.00 - £50',
                                '50-100' => '£50 - £100',
                                '100-150' => '£100 - £150',
                                '150-200' => '£150 - £200',
                                '200-above' => '£200+'
                            ];
                            foreach ($priceRanges as $range => $label) {
                                echo "<div class=\"flex justify-between mt-1\">
                                    <div>
                                        <p class=\"text-gray-500\">$label</p>
                                    </div>
                                    <div class=\"w-[50px] h-[18px]\">
                                        <input type=\"checkbox\" class=\"w-full h-full text-xs\" onchange=\"window.location.href='products.php?price=$range'\" " . ($priceFilter == $range ? 'checked' : '') . " />
                                    </div>
                                </div>";
                            }
                            ?>
                        </div>
                    </div>
                    <br>
                    <div class="mt-3">
                        <h1>Sort By</h1>
                        <div class="mt-3">
                            <div class="flex justify-between mt-1">
                                <div>
                                    <p class="text-gray-500">Price low to high</p>
                                </div>
                                <div class="w-[50px] h-[18px]">
                                    <input type="radio" name="sort" class="w-full h-full text-xs" onchange="window.location.href='products.php?sort=low-to-high'" <?= $sortOrder == 'low-to-high' ? 'checked' : '' ?> />
                                </div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <div>
                                    <p class="text-gray-500">Price high to low</p>
                                </div>
                                <div class="w-[50px] h-[18px]">
                                    <input type="radio" name="sort" class="w-full h-full text-xs" onchange="window.location.href='products.php?sort=high-to-low'" <?= $sortOrder == 'high-to-low' ? 'checked' : '' ?> />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-3/4">
            <div class="flex justify-between">
                <h2 class="text-xl font-bold my-4">All Products</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 gap-y-6">
                <?php
                $sql = "select p.product_id, p.name, p.price, p.image, p.shop_id,
                case 
                    when p.name like '%' || :search || '%' then 3
                    when c.category_name like '%' || :search || '%' then 2
                    when s.shop_name like '%' || :search || '%' then 1
                    else 0
                end as relevance_score
                from 
                product p
                inner join product_category c on p.category_id = c.category_id
                inner join shop s on p.shop_id = s.shop_id
                where (UPPER(p.name) like '%' || UPPER(:search) || '%' or UPPER(c.category_name) like '%' || UPPER(:search) || '%' or UPPER(s.shop_name) like '%' || UPPER(:search) || '%') and p.status = '1'";

                if ($categoryFilter) {
                    $sql .= " and c.category_name = :categoryFilter";
                }

                if ($priceFilter) {
                    switch ($priceFilter) {
                        case '0-50':
                            $sql .= " and p.price between 0 and 50";
                            break;
                        case '50-100':
                            $sql .= " and p.price between 50 and 100";
                            break;
                        case '100-150':
                            $sql .= " and p.price between 100 and 150";
                            break;
                        case '150-200':
                            $sql .= " and p.price between 150 and 200";
                            break;
                        case '200-above':
                            $sql .= " and p.price > 200";
                            break;
                    }
                }

                if ($sortOrder == 'low-to-high') {
                    $sql .= " order by p.price asc, relevance_score desc, p.product_id";
                } elseif ($sortOrder == 'high-to-low') {
                    $sql .= " order by p.price desc, relevance_score desc, p.product_id";
                } else {
                    $sql .= " order by relevance_score desc, p.product_id";
                }

                $stid = oci_parse($connection, $sql);
                oci_bind_by_name($stid, ':search', $search);

                if ($categoryFilter) {
                    oci_bind_by_name($stid, ':categoryFilter', $categoryFilter);
                }

                oci_execute($stid);
                $rows = [];
                while ($row = oci_fetch_assoc($stid)) {
                    $rows[] = $row;
                }

                if (empty($rows)) {
                    echo "<div class='col-span-4 text-center text-gray-500' style='font-size:80px'>No Result Found!!!</div>";
                } else {
                    foreach ($rows as $row) {
                        $productId = htmlspecialchars($row['PRODUCT_ID']);
                        $name = htmlspecialchars($row['NAME']);
                        $price = htmlspecialchars($row['PRICE']);
                        $image = htmlspecialchars($row['IMAGE']);
                        $shopId = htmlspecialchars($row['SHOP_ID']);
                        $sql = "SELECT sum(rating), count(*) FROM review WHERE PRODUCT_ID = '$productId'";
                        $stmt = oci_parse($connection, $sql);
                        oci_execute($stmt);
                        $rating = null;
                        if ($r = oci_fetch_assoc($stmt)) {
                            $c = $r["COUNT(*)"];
                            if ($c == 0)
                                $rating = null;
                            else {
                                $s = $r["SUM(RATING)"];
                                $rating = $s / $c;
                                $rating = number_format($rating, 1);
                            }
                        }

                        echo "<div>
                    <div class=\"card border-0 shadow product-item\">
                        <a href=\"../products/productspage.php?product_id=$productId&shop_id=$shopId\" class=\"text-decoration-none text-dark\">

                            <div class=\"product-image\">
                                <img src=\"../traderdashboard/productsImages/$image\" class=\"card-img-top\" alt=\"Product Image\" style=\"width:240px; height:160px;\" \">
                            </div>
                            <div class=\"product-info\">
                                <h3 class=\"product-name\">$name</h3>
                                <div>
                                <p class=\"product-stock\"> $rating <i class=\"fas fa-star\"></i></p>
                                </div>
                                <div class=\"product-price\">£ $price</div>

                            </div>
                            <button class=\"btn btn-success btn-add-to-cart\">Add to Cart</button>
                        </a>
                        
                    </div>

                </div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <?php require('../inc/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
    <script src="../js/carousel.js"></script>
</body>

</html>