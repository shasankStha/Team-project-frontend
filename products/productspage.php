<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/productspage.css">
    <style>
        /* Additional CSS for the star rating and popup */
        .star-rating {
            direction: ltr;
            display: inline-block;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #bbb;
            font-size: 20px;
            padding: 0;
            cursor: pointer;
        }

        .star-rating input:checked~label {
            color: #f2b600;
        }

        .review-popup-box {
            display: none;
            position: fixed;
            z-index: 2;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            max-width: 800px;
            width: 100%;
            max-height: 80%;
            overflow: hidden;
        }

        .reviews-container {
            max-height: 60%;
            overflow-y: auto;
            padding-right: 10px;
            margin-bottom: 10px;
        }

        .sticky-review-form {
            position: sticky;
            bottom: 0;
            background: white;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <?php
    session_start();
    include('../connection.php');
    $user_id = 0;
    $loggedInUserID = $_SESSION['userID'] ?? null;

    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;
    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
        $user_id = $_SESSION['userID'];
    } else {
        include('../inc/header.php');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['product_id'])) {
        $userId = $_POST['user_id'];
        $productId = $_POST['product_id'];
        $action = $_POST['action'];

        if ($action == 'add') {
            // Prepare the SQL statement to insert the favorite item
            $sql = "INSERT INTO FAVOURITE_ITEM (FAVOURITE_ITEM_ID, USER_ID, PRODUCT_ID) VALUES (null, :user_id, :product_id)";
            $stid = oci_parse($connection, $sql);
            oci_bind_by_name($stid, ':user_id', $user_id);
            oci_bind_by_name($stid, ':product_id', $productId);
        } else if ($action == 'remove') {
            // Prepare the SQL statement to delete the favorite item
            $sql = "DELETE FROM FAVOURITE_ITEM WHERE USER_ID = :user_id AND PRODUCT_ID = :product_id";
            $stid = oci_parse($connection, $sql);
            oci_bind_by_name($stid, ':user_id', $user_id);
            oci_bind_by_name($stid, ':product_id', $productId);
        }

        $flag = true;

        if (empty($user_id)) {
            $flag = false;
            echo "<script>alert('You have to be logged in!!!')</script>";
            echo "<script>window.location.href = '../login/login.php';</script>";
        }
        // Execute the statement
        if ($flag) {
            oci_execute($stid);
            echo "<script>
            window.location.href = window.location.href;
            </script>";
        }
    }

    $productId = $_GET['product_id'];
    $sql = "SELECT * FROM PRODUCT WHERE PRODUCT_ID = '$productId'";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);

    while ($row = oci_fetch_assoc($stid)) {
        $productId = $row['PRODUCT_ID'];
        $productName = $row['NAME'];
        $productImage = $row['IMAGE'];
        $productDescription = $row['DESCRIPTION'];
        $productPrice = $row['PRICE'];
        $StockAvailable = $row['STOCK_AVAILABLE'];
        $minOrder = $row['MIN_ORDER'];
        $maxOrder = $row['MAX_ORDER'];
        $allergyInfo = $row['ALLERGY_INFORMATION'];
    }

    $sql = "SELECT sum(rating), count(*) FROM review WHERE PRODUCT_ID = '$productId'";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $rating = null;
    $c = null;
    if ($row = oci_fetch_assoc($stid)) {
        $c = $row["COUNT(*)"];
        if ($c == 0)
            $rating = 0;
        else {
            $s = $row["SUM(RATING)"];
            $rating = $s / $c;
            $rating = number_format($rating, 1);
        }
    }

    $sql = "SELECT count(*) FROM favourite_item WHERE PRODUCT_ID = '$productId' and user_id = '$user_id'";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $count = null;
    if ($row = oci_fetch_assoc($stid)) {
        $count = $row["COUNT(*)"];
    }
    ?>

    <div class="main-container">
        <div class="container">
            <div class="left-section">
                <?php echo "<img src=\"../traderdashboard/productsImages/$productImage\" alt=\"Product Image\"/>" ?>
            </div>
            <div class="right-section">
                <div class="product-details">
                    <form method="post">
                        <h1 class="product-name"><?php echo "$productName"; ?></h1>
                        <div class="product-meta-info">
                            <p class="product-stock">About this item: <br> <?php echo "$productDescription"; ?></p>
                            <p class="product-stock">Stock Available: <?php echo "$StockAvailable"; ?></p>
                            <p class="product-stock">Min-Order: <?php echo "$minOrder"; ?></p>
                            <p class="product-stock">Max-Order: <?php echo "$maxOrder"; ?></p>
                            <p class="product-stock">Allergy Information <br> <?php echo "$allergyInfo"; ?></p>

                            <p class="product-stock"> Rating: <?php echo "$rating/5"; ?><i class="fas fa-star"></i> <?php echo " ($c)"; ?></p>
                        </div>
                        <div>
                            <p class="product-price">Price: £<?php echo "$productPrice"; ?></p>
                        </div>

                        <div class="quantity-and-favorite">
                            <div class="quantity-selector">
                                <label for="quantity" class="quantity-label">Quantity</label>
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-control minus">−</button>
                                    <input name='quantity' type="number" id="quantity" class="quantity-value" value="1" min="1">
                                    <button type="button" class="quantity-control plus">+</button>
                                </div>
                            </div>
                            <div class="favorite-icon-container">
                                <i id="heart" class="<?php echo ($count == 1) ? 'fas fa-heart favorite-icon favorited' : 'far fa-heart favorite-icon'; ?>"></i>
                            </div>
                        </div>
                        <div class="actions">

                            <button id="add-to-cart" type="submit" class="add-to-cart" name="add-to-cart">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['add-to-cart'])) {
        if (!$isLoggedIn) {
            echo "<script>alert('You have to login first.')</script>";
            echo "<script>window.location.href = '../login/login.php';</script>";
        } else {
            $productId = $_GET['product_id'];
            $quantity = $_POST['quantity'];
            $sql = "select cart_id from cart where user_id = '$user_id'";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            $cart_id = null;
            if ($row = oci_fetch_assoc($stid)) {
                $cart_id = $row['CART_ID'];
            }
            $sql = "select count(*) from cart_item where cart_id ='$cart_id'";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);
            $count = null;
            if ($row = oci_fetch_assoc($stid)) {
                $count = $row['COUNT(*)'];
            }
            if ($count >= 20) {
                echo "<script>alert('Cart limit is only 20 products.')</script>";
            } else {
                $sql = "select count(*) from cart_item where cart_id ='$cart_id' and product_id = '$productId'";
                $stid = oci_parse($connection, $sql);
                oci_execute($stid);
                if ($row = oci_fetch_assoc($stid)) {
                    $count = $row['COUNT(*)'];
                }
                if ($count == 1) {
                    echo "<script>alert('The item is already in the cart.')</script>";
                } else {


                    $sql = "insert into cart_item values(null,'$cart_id','$productId','$quantity')";
                    $stid = oci_parse($connection, $sql);
                    $exe = oci_execute($stid);
                    if ($exe) {
                        echo "<script>alert('Added to cart.')</script>";
                        echo "<script>window.location.href = window.location.href;</script>";
                    } else {
                        echo "<script>alert('Error!!!')</script>";
                    }
                }
            }
        }
    }
    ?>


    <form id="favoriteForm" method="POST" style="display: none;">
        <input type="hidden" name="user_id" value="<?php echo $loggedInUserID; ?>">
        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
        <input type="hidden" name="action" id="favoriteAction" value="">
    </form>


    <div class="reviews-section">
        <h2>Reviews</h2>
        <?php
        $product_id = $_GET['product_id'];
        // Fetch products for the shop
        $sql = "SELECT * FROM (
                SELECT r.review_id, r.rating, r.user_comment, r.review_date, u.first_name || ' ' || u.last_name AS name, c.PROFILE_PICTURE
                FROM review r
                INNER JOIN \"USER\" u ON u.user_id = r.user_id
                inner join customer c on c.user_id = r.user_id
                WHERE r.product_id = $product_id and r.status = '1'
                ORDER BY r.review_date DESC
            ) WHERE ROWNUM < 5";
        $stid = oci_parse($connection, $sql);
        oci_execute($stid);

        while ($row = oci_fetch_assoc($stid)) {
            $usernameReview = $row['NAME'];
            $userComment = $row['USER_COMMENT'];
            $rating = $row['RATING'];
            $rDate = $row['REVIEW_DATE'];
            $picture = $row['PROFILE_PICTURE'];
            echo "
                    <div class=\"review\">
                        <div class=\"review-header\">
                            <div class=\"username-and-stars\">
                                <div class=\"username\">
                                    <img src=\"../userprofile/image/$picture\" class=\"profile-pic\" alt=\"Profile Picture\">
                                    $usernameReview
                                </div>
                                <div class=\"review-stars\">
                ";

            // Display filled stars
            for ($i = 0; $i < $rating; $i++) {
                echo "<i class=\"fas fa-star\"></i>";
            }

            // Display empty stars
            for ($i = $rating; $i < 5; $i++) {
                echo "<i class=\"far fa-star\"></i>";
            }

            echo "
                                </div>
                            </div>
                            <div class=\"date\">$rDate</div>
                        </div>
                        <div class=\"review-body\">
                            <p>$userComment</p>
                        </div>
                    </div>
                ";
        }
        ?>
    </div>

    <button class="popup-button" onclick="toggleReviewPopup(event)">Write a Review</button>
    <div class="overlay" id="overlay" onclick="closeReviewPopup()" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; height: 100%; width: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);"></div>
    <div class="review-popup-box" id="review-popup" onclick="stopPropagation(event)">
        <span class="close-button" onclick="closeReviewPopup()">&times;</span>
        <h1 class="more-review-title">More Review</h1><br>
        <div class="reviews-container">
            <?php
            $product_id = $_GET['product_id'];
            // Fetch products for the shop
            $sql = "select r.review_id, r.rating,r.user_comment,r.review_date, u.first_name ||' '||u.last_name as name, c.PROFILE_PICTURE from review r
                        inner join \"USER\" u on u.user_id = r.user_id
                        inner join customer c on c.user_id = r.user_id where r.product_id = $product_id and r.status = '1'";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                $usernameReview = $row['NAME'];
                $userComment = $row['USER_COMMENT'];
                $rating = $row['RATING'];
                $rDate = $row['REVIEW_DATE'];
                $profile = $row['PROFILE_PICTURE'];


                echo "
                        <div class=\"username-container\">
                        <img src=\"../userprofile/image/$profile\" class=\"profile-pic\" alt=\"Profile Picture\">
                        <h5 class=\"username\">$usernameReview</h5>
                        </div>
                        <b>
                            <p class=\"dates\">$rDate</p>
                        </b>
                        <div class=\"review-stars-card\">
                ";

                // Display filled stars
                for ($i = 0; $i < $rating; $i++) {
                    echo "<i class=\"fas fa-star\"></i>";
                }

                // Display empty stars
                for ($i = $rating; $i < 5; $i++) {
                    echo "<i class=\"far fa-star\"></i>";
                }

                echo "
                        </div>
                        <div class=\"review-border\">
                            <p class=\"review-text\">$userComment</p>
                        </div><br>
                        ";
            }
            ?>
        </div>
        <div class="sticky-review-form">
            <form method="POST" action="">
                <div class="star-rating" id="popup-star-rating">
                    <input type="radio" id="popup-1-star" name="rating" value="1" />
                    <label for="popup-1-star" class="star">&#9733;</label>
                    <input type="radio" id="popup-2-stars" name="rating" value="2" />
                    <label for="popup-2-stars" class="star">&#9733;</label>
                    <input type="radio" id="popup-3-stars" name="rating" value="3" />
                    <label for="popup-3-stars" class="star">&#9733;</label>
                    <input type="radio" id="popup-4-stars" name="rating" value="4" />
                    <label for="popup-4-stars" class="star">&#9733;</label>
                    <input type="radio" id="popup-5-stars" name="rating" value="5" />
                    <label for="popup-5-stars" class="star">&#9733;</label>
                </div>
                <textarea class="review-input" placeholder="Write your review here..." name="review"></textarea>
                <button class="submit-button" type="submit" name="submit">Submit</button>
            </form>
        </div>

        <?php
        if (isset($_POST['submit'])) {
            $review = $_POST['review'];
            $userId = $loggedInUserID;
            $rating = $_POST['rating'];
            if ($rating == null || empty($rating))
                $rating = 1;
            else
                $rating = $_POST['rating'];

            if (empty($userId) || $userId == null) {
                echo "<script>alert('You have to be logged in!!!');</script>";
                echo "<script>window.location.href = '../login/login.php';</script>";
                exit();
            }
            $sql = "INSERT INTO review (review_id, rating, user_comment, review_date, status, product_id, user_id)
                    VALUES (null, :rating, :review, SYSDATE, '1', :product_id, :user_id)";
            $stid = oci_parse($connection, $sql);
            oci_bind_by_name($stid, ':rating', $rating);
            oci_bind_by_name($stid, ':review', $review);
            oci_bind_by_name($stid, ':product_id', $product_id);
            oci_bind_by_name($stid, ':user_id', $userId);

            $result = oci_execute($stid);

            if ($result) {
                echo "<script>
                alert('Review submitted successfully!');
                window.location.href = window.location.href + '?success=1';
                </script>";
                exit();
            } else {
                $e = oci_error($stid);
                echo "<script>alert('Error: " . htmlentities($e['message']) . "');</script>";
            }
        }
        ?>
    </div>

    <div class="similar-products-section">
        <h2>Similar Products</h2>
        <div class="similar-products-container">
            <?php
            $shop_id = (int)$_GET['shop_id'];
            // Fetch products for the shop
            $sql = "select * from (SELECT * FROM PRODUCT WHERE SHOP_ID = :shop_id AND PRODUCT_ID != :product_id AND STATUS = '1') where rownum<5";
            $stid = oci_parse($connection, $sql);
            oci_bind_by_name($stid, ':shop_id', $shop_id);
            oci_bind_by_name($stid, ':product_id', $productId);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                $productId = $row['PRODUCT_ID'];
                $pName = $row['NAME'];
                $pPrice = $row['PRICE'];
                $pImage = $row['IMAGE'];
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

                echo "
                    <div class=\"similar-product-item\">
                        <a href=\"?product_id=$productId&shop_id=$shop_id\" class=\"text-decoration-none text-dark\">
                            <div class=\"similar-product-image\">
                                <img src=\"../traderdashboard/productsImages/$pImage\" alt=\"Product Image\" style=\"width:240px; height:160px;\" />
                            </div>
                            <div class=\"similar-product-info\">
                                <h3 class=\"similar-product-name\">$pName</h3>
                                <div style=\"display: inline-block;\">
                                        $rating
                                        <p class=\"product-rating\" style=\"display: inline; margin: 0;\"> 
                                            <i class=\"fas fa-star\"></i>
                                        </p>
                                </div>
                                <div class=\"similar-product-price\">£ $pPrice</div>
                            </div>
                        <button class=\"btn btn-success btn-add-to-cart\">Add to Cart</button>
                        </a>
                    </div>
                ";
            }
            ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('heart').addEventListener('click', function() {
                var favoriteForm = document.getElementById('favoriteForm');
                var favoriteAction = document.getElementById('favoriteAction');
                if (this.classList.contains('fas')) {
                    favoriteAction.value = 'remove';
                } else {
                    favoriteAction.value = 'add';
                }
                favoriteForm.submit();
            });
        });

        // Quantity selector 
        document.addEventListener('DOMContentLoaded', function() {
            var quantityInput = document.getElementById('quantity');
            var minusButton = document.querySelector('.quantity-control.minus');
            var plusButton = document.querySelector('.quantity-control.plus');

            minusButton.addEventListener('click', function() {
                var currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });

            plusButton.addEventListener('click', function() {
                var currentValue = parseInt(quantityInput.value);
                if (currentValue < <?php echo "$maxOrder" ?>)
                    quantityInput.value = currentValue + 1;
                else
                    alert(`You can order a maximum of <?php echo "$maxOrder" ?> units for this product.`);
            });
        });

        // For more review
        function toggleReviewPopup(event) {
            var overlay = document.getElementById('review-popup');
            overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';
            event.stopPropagation(); // Prevent click event from propagating to overlay
        }

        function closeReviewPopup() {
            var overlay = document.getElementById('review-popup');
            overlay.style.display = 'none';
        }

        function stopPropagation(event) {
            event.stopPropagation();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('heart').addEventListener('click', function() {
                this.classList.toggle('fas');
                this.classList.toggle('far');
                this.classList.toggle('favorited'); // Toggles the red color
            });
        });

        // Quantity selector
        // document.addEventListener('DOMContentLoaded', function() {
        //     var quantityInput = document.getElementById('quantity');
        //     var minusButton = document.querySelector('.quantity-control.minus');
        //     var plusButton = document.querySelector('.quantity-control.plus');

        //     minusButton.addEventListener('click', function() {
        //         var currentValue = parseInt(quantityInput.value);
        //         if (currentValue > 1) {
        //             quantityInput.value = currentValue - 1;
        //         }
        //     });

        //     plusButton.addEventListener('click', function() {
        //         var currentValue = parseInt(quantityInput.value);
        //         quantityInput.value = currentValue + 1;
        //     });
        // });

        // Toggle review popup
        function toggleReviewPopup(event) {
            console.log("Toggling review popup...");
            var overlay = document.getElementById('overlay');
            var popup = document.getElementById('review-popup');
            overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';
            popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
            event.stopPropagation(); // Prevent click event from propagating to overlay
        }

        function closeReviewPopup() {
            console.log("Closing review popup...");
            var overlay = document.getElementById('overlay');
            var popup = document.getElementById('review-popup');
            overlay.style.display = 'none';
            popup.style.display = 'none';
        }

        function stopPropagation(event) {
            console.log("Stopping event propagation...");
            event.stopPropagation();
        }

        // Star rating for popup
        document.addEventListener('DOMContentLoaded', function() {
            const popupStars = document.querySelectorAll('#popup-star-rating .star');
            popupStars.forEach(star => {
                star.addEventListener('click', function() {
                    popupStars.forEach(s => s.style.color = '#bbb');
                    this.style.color = '#f2b600';
                    let prev = this.previousElementSibling;
                    while (prev) {
                        prev.style.color = '#f2b600';
                        prev = prev.previousElementSibling;
                    }
                });
            });
        });
    </script>
    <?php require('../inc/footer.php'); ?>
</body>

</html>