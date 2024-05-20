<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/productspage.css">
</head>

<body>
    <?php
    session_start();
    include('../connection.php');

    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;
    $loggedInUserID = $_SESSION['userID'] ?? null;

    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
    }

    $productId = null;
    $productName = null;
    $productImag = null;
    $productDescription = null;
    $productPrice = null;
    $StockAvailable = null;
    $minOrder = null;
    $allergyInfo = null;
    $usernameReview = null;
    $userComment = null;
    $rating = null;
    $rDate = null;
    ?>

    <?php
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

    ?>

    <div class="main-container">
        <div class="container">
            <div class="left-section">
                <?php echo "<img src=\"../traderdashboard/productsImages/$productImage\" alt=\"Product Image\"/>" ?>
            </div>
            <div class="right-section">
                <div class="product-details">
                    <h1 class="product-name"><?php echo "$productName"; ?></h1>
                    <div class="product-meta-info">
                        <p class="product-desc"><?php echo "$productDescription"; ?></p>
                        <p class="product-price">Price: £<?php echo "$productPrice"; ?></p>
                        <p class="product-stock">Stock Available: <?php echo "$StockAvailable"; ?></p>
                        <p class="product-min">Min-Order: <?php echo "$minOrder"; ?></p>
                        <p class="product-max">Max-Order: <?php echo "$maxOrder"; ?></p>
                        <p class="product-allergy">Allergy Information: <?php echo "$allergyInfo"; ?></p>
                    </div>
                    <div class="quantity-and-favorite">
                        <div class="quantity-selector">
                            <label for="quantity" class="quantity-label">Quantity</label>
                            <div class="quantity-controls">
                                <button type="button" class="quantity-control minus">−</button>
                                <input type="number" id="quantity" class="quantity-value" value="1">
                                <button type="button" class="quantity-control plus">+</button>
                            </div>
                        </div>
                        <div class="favorite-icon-container">
                            <i id="heart" class="far fa-heart favorite-icon"></i>
                        </div>
                    </div>
                    <div class="actions">
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="reviews-section">
            <h2>Reviews</h2>
            <?php
            $product_id = $_GET['product_id'];
            // Fetch products for the shop
            $sql = "SELECT * FROM (
                SELECT r.review_id, r.rating, r.user_comment, r.review_date, u.first_name || ' ' || u.last_name AS name
                FROM review r
                INNER JOIN \"USER\" u ON u.user_id = r.user_id
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

                echo "
                    <div class=\"review\">
                        <div class=\"review-header\">
                            <div class=\"username-and-stars\">
                                <div class=\"username\">
                                    <i class=\"fa-solid fa-user\"></i>
                                    $usernameReview
                                </div>
                                
                                <div class=\"review-stars\">
                                    <i class=\"fas fa-star\"></i>
                                    <i class=\"fas fa-star\"></i>
                                    <i class=\"fas fa-star\"></i>
                                    <i class=\"fas fa-star\"></i>
                                    <i class=\"far fa-star\"></i>
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

        <button class="popup-button" onclick="toggleReviewPopup(event)">More Review</button>
        <div class="overlay" id="overlay" onclick="closeReviewPopup()" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; height: 100%; width: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5);"></div>
        <div class="review-popup-box" id="review-popup" onclick="stopPropagation(event)">
            <span class="close-button" onclick="closeReviewPopup()">&times;</span>
            <h1 class="more-review-title">More Review</h1><br>
            <?php
            $product_id = $_GET['product_id'];
            // Fetch products for the shop
            $sql = "select r.review_id, r.rating,r.user_comment,r.review_date, u.first_name ||' '||u.last_name as name from review r
                    inner join \"USER\" u on u.user_id = r.user_id where r.product_id = $product_id and r.status = '1'";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                $usernameReview = $row['NAME'];
                $userComment = $row['USER_COMMENT'];
                $rating = $row['RATING'];
                $rDate = $row['REVIEW_DATE'];

                echo "
                    <div class=\"username-container\">
                    <img src=\"user_profile.jpg\" class=\"profile-pic\" alt=\"Profile Picture\">
                    <h5 class=\"username\">$usernameReview</h5>
                    </div>
                    <b>
                        <p class=\"dates\">$rDate</p>
                    </b>
                    <div class=\"review-border\">
                        <p class=\"review-text\">$userComment</p>
                    </div><br>
                    ";
            }
            ?>

            <form method="POST" action="">
                <textarea class="review-input" placeholder="Write your review here..." name="review"></textarea>
                <button class="submit-button" type="submit" name="submit">Submit</button>
            </form>

            <?php
            if (isset($_POST['submit'])) {
                $review = $_POST['review'];
                $userId = $loggedInUserID;
                $rating = 3; // Example rating value. This should be fetched from user input if you have a rating system in the form.

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
    </div>
    </div>
    </div>
    </div>
    <div class="similar-products-section">
        <h2>Similar Products</h2>

        <div class="similar-products-container">
            <?php
            $shop_id = (int)$_GET['shop_id'];
            // Fetch products for the shop
            $sql = "SELECT * FROM PRODUCT WHERE SHOP_ID = $shop_id and product_id != $productId";
            $stid = oci_parse($connection, $sql);
            oci_execute($stid);

            while ($row = oci_fetch_assoc($stid)) {
                $productId = $row['PRODUCT_ID'];
                $pName = $row['NAME'];
                $pPrice = $row['PRICE'];
                $pImage = $row['IMAGE'];

                echo "
                <div class=\"similar-product-item\">
                <a href=\"?product_id=$productId&shop_id=$shop_id\" class=\"text-decoration-none text-dark\">
                    <div class=\"similar-product-image\">
                        <img src=\"../traderdashboard/productsImages/$pImage\" alt=\"Product Image\" style=\"width:110px;\" />
                    </div>
                    <div class=\"similar-product-info\">
                        <h3 class=\"similar-product-name\">$pName</h3>
                        <i class=\"fas fa-star\"></i>
                        <i class=\"fas fa-star\"></i>
                        <i class=\"fas fa-star\"></i>
                        <i class=\"fas fa-star\"></i>
                        <i class=\"far fa-star\"></i>
                        <p class=\"similar-product-price\">£$pPrice</p>
                        </a>
                        <button class=\"btn btn-success btn-add-to-cart\">Add to Cart</button>
                    </div>

                </div>
            ";
            }
            ?>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('heart').addEventListener('click', function() {
                    this.classList.toggle('fas');
                    this.classList.toggle('far');
                    this.classList.toggle('favorited'); // Toggles the red color
                });
            });

            //quantity selector 
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
                    quantityInput.value = currentValue + 1;
                });
            });


            //-------------for more review--------------------//

            function toggleReviewPopup(event) {
                console.log("Toggling review popup...");
                var overlay = document.getElementById('review-popup');
                overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';
                event.stopPropagation(); // Prevent click event from propagating to overlay
            }
            // Function to toggle the display of overlay and review popup box
            function togglePopup() {
                var overlay = document.getElementById('overlay');
                var popup = document.getElementById('reviewPopup');
                overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';
                popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
            }

            function closeReviewPopup() {
                console.log("Closing review popup...");
                var overlay = document.getElementById('review-popup');
                overlay.style.display = 'none';
            }

            function stopPropagation(event) {
                console.log("Stopping event propagation...");
                event.stopPropagation();
            }
            // Select the submit button
            var submitButton = document.querySelector('.submit-button');
        </script>
        <?php require('../inc/footer.php'); ?>
</body>

</html>