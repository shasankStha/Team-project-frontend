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
    include('../connection.php');
    session_start();

    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
    }
    ?>
    <div class="main-container">
        <div class="container">
            <div class="left-section">
                <img src="../images/fish.jpg" alt="Fish Image">
            </div>
            <div class="right-section">
                <div class="product-details">
                    <h1 class="product-name">Aquatic Wonder</h1>
                    <div class="product-meta-info">
                        <p class="product-desc">Discover the beauty of the aquatic life with this vibrant and
                            mesmerizing goldfish.</p>
                        <p class="product-price">Price: $29.99</p>
                        <p class="product-stock">Stock Available: 15</p>
                        <p class="product-min">Minimum Order: 1</p>
                        <p class="product-max">Maximum Order: 5</p>
                        <p class="product-allergy">Allergy Information: Hypoallergenic</p>
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
                        <button class="buy-now">Buy Now</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="reviews-section">
            <h2>Reviews</h2>
            <div class="review">
                <div class="review-header">
                    <div class="username-and-stars">
                        <div class="username">
                            <i class="fa-solid fa-user"></i>
                            Username
                        </div>
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                    <div class="date">2024/04/12</div>
                </div>
                <div class="review-body">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima ad autem cum beatae reiciendis,
                        repellat placeat quibusdam, voluptas sed quia cupiditate debitis aliquam reprehenderit commodi
                        rerum fugit impedit omnis eos.</p>
                </div>
            </div>
            <div class="review">
                <div class="review-header">
                    <div class="username-and-stars">
                        <div class="username">
                            <i class="fa-solid fa-user"></i>
                            Username
                        </div>
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                    <div class="date">2024/04/12</div>
                </div>
                <div class="review-body">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima ad autem cum beatae reiciendis,
                        repellat placeat quibusdam, voluptas sed quia cupiditate debitis aliquam reprehenderit commodi
                        rerum fugit impedit omnis eos.</p>
                </div>
            </div>
            <div class="review">
                <div class="review-header">
                    <div class="username-and-stars">
                        <div class="username">
                            <i class="fa-solid fa-user"></i>
                            Username
                        </div>
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                    <div class="date">2024/04/12</div>
                </div>
                <div class="review-body">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima ad autem cum beatae reiciendis,
                        repellat placeat quibusdam, voluptas sed quia cupiditate debitis aliquam reprehenderit commodi
                        rerum fugit impedit omnis eos.</p>
                </div>
            </div>
            <div class="review">
                <div class="review-header">
                    <div class="username-and-stars">
                        <div class="username">
                            <i class="fa-solid fa-user"></i>
                            Username
                        </div>
                        <div class="review-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                    <div class="date">2024/04/12</div>
                </div>
                <div class="review-body">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima ad autem cum beatae reiciendis,
                        repellat placeat quibusdam, voluptas sed quia cupiditate debitis aliquam reprehenderit commodi
                        rerum fugit impedit omnis eos.</p>
                </div>
            </div>
            <div class="button-container" style="text-align: center;">
            <div class="button-container" style="text-align: center;">
            <button class="popup-button" onclick="togglePopup(event)">More Review</button>
    <div class="overlay" onclick="closePopup()">
      <div class="review-box" onclick="stopPropagation(event)">
        <span class="close-button" onclick="closePopup()">&times;</span>
        <h1>More Reviews</h1>
        <div class="username-container">
          <img src="user_profile.jpg" class="profile-pic" alt="Profile Picture">
          <h3 class="username">Username1</h3>
        </div>
        <b><p class="date">Date:00/00/00</p></b>
        <div class="review-border">
          <h4>Review</h4>
          <p class="review-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tempor nulla eu odio vehicula eleifend.</p>
        </div><br>
        <div class="username-container">
          <img src="user_profile.jpg" class="profile-pic" alt="Profile Picture">
          <h3 class="username">Username2</h3>
        </div>
        <b><p class="date">Date:00/00/00</p></b>
        <div class="review-border">
          <h4>Review</h4>
          <p class="review-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus tempor nulla eu odio vehicula eleifend.</p>
        </div>
        <br>
        <h3>Write a Review</h3>
        <div class="username-container">
          <img src="user_profile.jpg" class="profile-pic" alt="Profile Picture">
          <h3 class="username">Username3</h3>
        </div>
        <b><p class="date">Date:00/00/00</p></b>
        <textarea class="review-input" placeholder="Write your review here..."></textarea>
        <button class="submit-button">Submit</button>
      </div>
    </div>
                
            </div>
        </div>
            </div>
        </div>
    </div>
    <div class="similar-products-section">
        <h2>Similar Products</h2>
        <div class="similar-products-container">
            <!-- Product Item -->
            <div class="similar-product-item">
                <a href="../products/productspage.php" class="text-decoration-none text-dark">
                    <div class="similar-product-image">
                        <img src="../images/fish.jpg" alt="Product 1">
                    </div>
                    <div class="similar-product-info">
                        <h3 class="similar-product-name">Product Name 1</h3>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <p class="similar-product-price">$19.99</p>
                        <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                    </div>
                </a>
            </div>
            <div class="similar-product-item">
                <a href="../products/productspage.php" class="text-decoration-none text-dark">
                    <div class="similar-product-image">
                        <img src="../images/fish.jpg" alt="Product 1">
                    </div>
                    <div class="similar-product-info">
                        <h3 class="similar-product-name">Product Name 1</h3>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <p class="similar-product-price">$19.99</p>
                        <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                    </div>
                </a>
            </div>
            <div class="similar-product-item">
                <a href="../products/productspage.php" class="text-decoration-none text-dark">
                    <div class="similar-product-image">
                        <img src="../images/fish.jpg" alt="Product 1">
                    </div>
                    <div class="similar-product-info">
                        <h3 class="similar-product-name">Product Name 1</h3>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <p class="similar-product-price">$19.99</p>
                        <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                    </div>
                </a>
            </div>
            <div class="similar-product-item">
                <a href="../products/productspage.php" class="text-decoration-none text-dark">
                    <div class="similar-product-image">
                        <img src="../images/fish.jpg" alt="Product 1">
                    </div>
                    <div class="similar-product-info">
                        <h3 class="similar-product-name">Product Name 1</h3>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                        <p class="similar-product-price">$19.99</p>
                        <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
                    </div>
                </a>
            </div>
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

            // Function to toggle the popup
    function togglePopup(event) {
      var overlay = document.querySelector('.overlay');
      overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';
      event.stopPropagation(); // Prevent click event from propagating to overlay
    }

    // Function to close the popup
    function closePopup() {
      var overlay = document.querySelector('.overlay');
      overlay.style.display = 'none';
    }

    // Function to stop event propagation
    function stopPropagation(event) {
      event.stopPropagation();
    }
        </script>
        <?php require('../inc/footer.php'); ?>
</body>

</html>