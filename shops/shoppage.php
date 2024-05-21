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
  <?php
  session_start();
  include('../connection.php');
  $shopId = $_GET['shop_id'];

  // Fetch shop information in a single query
  $sql = "SELECT SHOP_NAME, SHOP_DESCRIPTION, CONTACT_NUMBER, PICTURE, LOCATION FROM SHOP WHERE SHOP_ID = :shop_id";
  $stid = oci_parse($connection, $sql);
  oci_bind_by_name($stid, ':shop_id', $shopId);
  oci_execute($stid);

  $shop_name = $description = $contact_number = $location = null;
  if ($row = oci_fetch_assoc($stid)) {
    $shop_name = $row['SHOP_NAME'];
    $description = $row['SHOP_DESCRIPTION'];
    $contact_number = $row['CONTACT_NUMBER'];
    $location = $row['LOCATION'];
    $image = $row['PICTURE'];
  }

  // Determine header based on login status
  $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;
  if ($isLoggedIn) {
    include('../inc/loggedin_header.php');
  } else {
    include('../inc/header.php');
  }
  ?>

  <div class="shop-name">
    <h1><?php echo htmlspecialchars($shop_name); ?></h1>
  </div>
  <div class="content-wrapper">
    <div class="shop-main-container">
      <div class="shop-image">
        <?php echo "<img src=../shop_image/$image alt=\"Product Image\"/>" ?>
      </div>

      <div class="description-container">
        <div class="description-heading">Description</div>
        <div class="description-text">
          <?php echo htmlspecialchars($description); ?>
        </div>
      </div>
      <div class="shop-info">
        <p class="contact"><i class="fas fa-phone-alt"></i> Contact Number: <em style="font-size:20px;margin-left:12px;-webkit-text-stroke: 1px #001f3f;"><?php echo htmlspecialchars($contact_number); ?></em></p>
        <p class="location"><i class="fas fa-map-marker-alt"></i> Location: <em style="font-size:20px;margin-left:13px;-webkit-text-stroke: 1px #001f3f;"><?php echo htmlspecialchars($location); ?></em></p>
      </div>
    </div>
    <div class="products-container">
      <h2>Products</h2>
      <div class="products-grid">
        <?php
        // Fetch products for the shop
        $sql = "SELECT * FROM PRODUCT WHERE SHOP_ID = :shop_id and status = '1'";
        $stid = oci_parse($connection, $sql);
        oci_bind_by_name($stid, ':shop_id', $shopId);
        oci_execute($stid);

        while ($row = oci_fetch_assoc($stid)) {
          $productId = $row['PRODUCT_ID'];
          $productId = htmlspecialchars($row['PRODUCT_ID']);
          $name = htmlspecialchars($row['NAME']);
          $price = htmlspecialchars($row['PRICE']);
          $image = htmlspecialchars($row['IMAGE']);

          echo "
          <div class=\"product-item\">
          <a href='../products/productspage.php?product_id=$productId&shop_id=$shopId' class='text-decoration-none text-dark'>
              <div class=\"product-image\">
                <img src=\"../traderdashboard/productsImages/$image\" alt=\"Product Image\" style=\"width:110px;\" />
                <div class=\"favorite-icon\" onclick=\"toggleFavorite(this)\" data-product-id=\"$productId\">
                </div>
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
                <button class=\"btn btn-success btn-add-to-cart\">Add to Cart</button>
                </a>
                
              </div>
          </div>";
        }
        ?>
      </div>
    </div>
  </div>
  <?php require('../inc/footer.php'); ?>
  <script>
    // JavaScript for handling favorites and ratings
    document.addEventListener("DOMContentLoaded", (event) => {
      const ratingContainers = document.querySelectorAll(".product-rating");

      ratingContainers.forEach((container) => {
        const stars = container.querySelectorAll(".fas, .far");
        stars.forEach((star) => {
          star.addEventListener("click", function() {
            setRating(star, container);
          });
        });
      });

      function setRating(selectedStar, container) {
        const ratingValue = selectedStar.getAttribute("data-value");
        updateStars(ratingValue, container);
      }

      function updateStars(rating, container) {
        const stars = container.querySelectorAll(".fas, .far");
        stars.forEach((star) => {
          if (star.getAttribute("data-value") <= rating) {
            star.classList.add("fas");
            star.classList.remove("far");
          } else {
            star.classList.add("far");
            star.classList.remove("fas");
          }
        });
      }
    });

    function toggleFavorite(element) {
      const heartIcon = element.querySelector("i");
      if (heartIcon.classList.contains("far")) {
        heartIcon.classList.remove("far");
        heartIcon.classList.add("fas");
      } else {
        heartIcon.classList.remove("fas");
        heartIcon.classList.add("far");
      }

      // Update the favorite status in the database using AJAX
      const productId = element.getAttribute("data-product-id");
      const isFavorite = heartIcon.classList.contains("fas");
      updateFavoriteStatus(productId, isFavorite);
    }

    function updateFavoriteStatus(productId, isFavorite) {
      fetch("update_favorite_status.php", {
          method: "POST",
          body: JSON.stringify({
            productId: productId,
            isFavorite: isFavorite
          }),
          headers: {
            "Content-Type": "application/json"
          },
        })
        .then(response => response.json())
        .then(data => console.log(data.message))
        .catch(error => console.error("Error:", error));
    }
  </script>
</body>

</html>
