<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Shop Page</title>
  <link rel="stylesheet" href="../css/shoppage.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />


</head>

<body>
  <?php require ('../inc/header.php'); ?>
  <div class="shop-name">
    <h1>Shop Name</h1>
  </div>
  <div class="content-wrapper">
    <div class="shop-main-container">
      <div class="shop-image">
        <img src="../images/p1.jpg" alt="Shop Image" />
      </div>
      <div class="description-container">
        <div class="description-heading">Description</div>
        <div class="description-text">
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum iste
          tempore voluptatum fuga? Officiis corporis quisquam, ullam quae
          quas, facere rerum assumenda similique dolores, placeat molestias
          doloremque aliquam mollitia. Doloremque. Lorem ipsum dolor sit amet
          consectetur adipisicing elit. Cupiditate eos iure ipsa enim
          architecto impedit animi, commodi eveniet deleniti nemo
          reprehenderit maxime cumque, ullam unde rem autem obcaecati, dolorum
          voluptas. Lorem ipsum dolor sit amet, consectetur adipisicing elit.
          Officiis possimus dolorem libero impedit voluptatem cum sed optio
          laboriosam ullam explicabo? Cum suscipit adipisci explicabo
          voluptatem esse animi eligendi reiciendis dolorum?
        </div>
      </div>
      <div class="shop-info">
        <p class="contact">Contact Number: 123-456-7890</p>
        <p class="address">Address details go here...</p>
        <p class="location">Location details go here...</p>
      </div>
    </div>

    <!-- Right side: Products Section -->
    <div class="products-container">
      <h2>Products</h2>
      <div class="products-grid">
        <!-- Product Items -->
        <div class="product-item">
          <a href="../products/productspage.php" class="text-decoration-none text-dark">
            <div class="product-image">
              <img src="../images/p1.jpg" alt="Product Name" />
              <div class="favorite-icon" onclick="toggleFavorite(this)">
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Product Name</h3>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
              <div class="product-price">20$</div>
              <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
            </div>
          </a>
        </div>

        <div class="product-item">
          <a href="../products/productspage.php" class="text-decoration-none text-dark">
            <div class="product-image">
              <img src="../images/p1.jpg" alt="Product Name" />
              <div class="favorite-icon" onclick="toggleFavorite(this)">
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Product Name</h3>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
              <div class="product-price">20$</div>
              <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
            </div>
          </a>
        </div>

        <div class="product-item">
          <a href="../products/productspage.php" class="text-decoration-none text-dark">
            <div class="product-image">
              <img src="../images/p1.jpg" alt="Product Name" />
              <div class="favorite-icon" onclick="toggleFavorite(this)">
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Product Name</h3>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
              <div class="product-price">20$</div>
              <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
            </div>
          </a>
        </div>

        <div class="product-item">
          <a href="../products/productspage.php" class="text-decoration-none text-dark">
            <div class="product-image">
              <img src="../images/p1.jpg" alt="Product Name" />
              <div class="favorite-icon" onclick="toggleFavorite(this)">
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Product Name</h3>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
              <div class="product-price">20$</div>
              <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
            </div>
          </a>
        </div>

        <div class="product-item">
          <a href="../products/productspage.php" class="text-decoration-none text-dark">
            <div class="product-image">
              <img src="../images/p1.jpg" alt="Product Name" />
              <div class="favorite-icon" onclick="toggleFavorite(this)">
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Product Name</h3>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
              <div class="product-price">20$</div>
              <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
            </div>
          </a>
        </div>

        <div class="product-item">
          <a href="../products/productspage.php" class="text-decoration-none text-dark">
            <div class="product-image">
              <img src="../images/p1.jpg" alt="Product Name" />
              <div class="favorite-icon" onclick="toggleFavorite(this)">
              </div>
            </div>
            <div class="product-info">
              <h3 class="product-name">Product Name</h3>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
              <div class="product-price">20$</div>
              <button class="btn btn-success btn-add-to-cart">Add to Cart</button>
            </div>
          </a>
        </div>

        

       

        <!-- Repeat for other products -->
      </div>
    </div>
  </div>
  <?php require ('../inc/footer.php'); ?>
  <script>
    document.addEventListener("DOMContentLoaded", (event) =>
      function toggleFavorite(element) {
        const heartIcon = element.querySelector("i");
        if (heartIcon.classList.contains("far")) {
          heartIcon.classList.remove("far");
          heartIcon.classList.add("fas");
          // Optional: Add logic to handle the action of marking as favorite
        } else {
          heartIcon.classList.remove("fas");
          heartIcon.classList.add("far");
          // Optional: Add logic to handle the action of removing from favorites
        }

        // Example: Update the favorite status in the database using AJAX
        const productId = element.getAttribute("data-product-id"); // Assuming you have a product ID attribute
        const isFavorite = heartIcon.classList.contains("fas");
        updateFavoriteStatus(productId, isFavorite);
      }

  function updateFavoriteStatus(productId, isFavorite) {
        // AJAX request to a PHP script that updates the product's favorite status
        fetch("update_favorite_status.php", {
          method: "POST",
          body: JSON.stringify({
            productId: productId,
            isFavorite: isFavorite,
          }),
          headers: { "Content-Type": "application/json" },
        })
          .then((response) => response.json())
          .then((data) => console.log(data.message))
          .catch((error) => console.error("Error:", error));
      }
});
  </script>
</body>

</html>