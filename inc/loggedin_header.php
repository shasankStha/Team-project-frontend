<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
$user_id = $_SESSION['userID'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
  $_SESSION['search'] = $_POST['search'];
  if (!empty($_SESSION['search'])) {
    header("Location: ../products/products.php");
  } else {
    echo "<script>
    alert('Search bar is empty!!!');
    window.location.href = window.location.href;
    </script>";
  }
}
if (isset($_SESSION['search'])) {
  $search = isset($_SESSION['search']) ? $_SESSION['search'] : ($_GET['search'] ?? "");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: white;
      padding: 10px 20px;
    }

    .logo {
      display: flex;
      align-items: center;
    }

    .logo img {
      width: 200px;
    }

    .search-bar {
      display: flex;
      align-items: center;
      margin-left: auto;
      margin-right: 50px;
    }

    .search-bar input {
      padding: 8px;
      margin-right: 5px;
      border: 1px solid #ccc;
      border-radius: 20px;
      flex-grow: 1;
    }

    .search-button {
      padding: 6px 10px;
      background: transparent;
      border: none;
      color: black;
      cursor: pointer;
    }

    .search-button i {
      font-size: 16px;
    }

    .menu {
      display: flex;
      align-items: center;
    }

    .menu a {
      color: black;
      padding: 10px;
      text-decoration: none;
      font-size: 16px;
      margin-left: 10px;
      transition: color 0.3s;
    }

    .menu a:hover {
      color: white;
      background-color: black;
      border-radius: 20px;
    }

    .toggle-button {
      display: none;
      flex-direction: column;
      cursor: pointer;
    }

    .toggle-button .bar {
      height: 3px;
      width: 25px;
      background-color: black;
      margin: 3px 0;
    }

    @media (max-width: 600px) {
      .navbar {
        display: flex;
        flex-direction: column;
      }

      .logo img {
        width: 100px;
      }

      .search-bar {
        margin-right: 40px;
        display: flex;
        margin-bottom: 5px;
      }

      .menu {
        width: 100%;
        display: none;
        flex-direction: column;
        align-items: center;
      }

      .menu.active {
        display: flex;
      }

      .toggle-button {
        display: flex;
        position: absolute;
        right: 10px;
        top: 10px;
      }

      .ddmenu {
        z-index: 1 !important;
      }
    }

    @media (min-width: 600px) {
      .navbar .menu a .label {
        display: none;
      }
    }

    .sub-navbar {
      display: flex;
      justify-content: space-around;
      background-color: #2b2f33;
      color: white;
      padding: 10px 0;
      margin-bottom: 20px;
      text-align: center;
    }

    .sub-navbar a {
      color: white;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-block;
      margin: 0 5px;
      padding: 8px 16px;
    }

    .sub-navbar a:hover {
      background-color: #f0f0f0;
      color: black;
      border-radius: 30px;
    }

    .user-icon,
    .notification-icon {
      position: relative;
      display: inline-block;
    }

    #dropdown-menu,
    #notification-dropdown {
      display: none;
      flex-direction: column;
      position: absolute;
      background-color: #2b2f33;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      right: 0;
      /* z-index: 1; */
    }

    #backgroundBlur {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      /* Initially hidden */
      z-index: 1000;
    }

    .user-icon:hover #dropdown-menu,
    .notification-icon:hover #notification-dropdown {
      display: flex;
    }

    #dropdown-menu a,
    #notification-dropdown a {
      color: white;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    #dropdown-menu a:hover,
    #notification-dropdown a:hover {
      background-color: #f0f0f0;
      color: black;
      border-radius: 30px;
    }

    /* Dropdown menu for notifications */
    .notifications-dropdown {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 200px;
      min-height: 200px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
      overflow: hidden;
      overflow-x: auto;
      right: 0;
    }

    .notifications-dropdown a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      white-space: nowrap;
    }

    .notifications-icon:hover .notifications-dropdown {
      display: block;
    }

    .badge {
      background-color: red;
      color: white;
      width: 20px;
      height: 20px;
      padding: 4px 8px;
      border-radius: 100%;
      position: absolute;
      top: -8px;
      right: -8px;
      margin-top: 2em;
      margin-right: 10em;
      margin-top: 2.2em;
    }

    .cart-popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 40%;
      max-height: 80%;
      overflow-y: auto;
      background-color: white;
      padding: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      display: none;
      z-index: 1001;
    }

    .cart-popup h2 {
      margin: 0;
      padding-bottom: 20px;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .cart-popup h2 .close-btn {
      cursor: pointer;
      font-size: 20px;
      color: black;
    }

    .clear-cart-btn {
      background: #FF3B30;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      margin-bottom: 20px;
      font-size: 14px;
      margin-left: 450px;
      margin-top: 12px;
    }

    .clear-cart-btn:hover {
      background: #CC2B24;
    }

    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 10px 0;
      padding: 10px;
      background: #f7f7f7;
      border-radius: 8px;
    }

    .cart-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    }

    .cart-item .product-details {
      flex: 1;
      margin: 0 10px;
      text-align: center;
    }

    .cart-item .quantity-controls {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 5px;
    }

    .cart-item .quantity-controls button {
      background: black;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
      margin: 0 5px;
      transition: background 0.3s;
    }

    .cart-item .quantity-controls button:hover {
      background: grey;
    }

    .cart-popup .subtotal {
      display: flex;
      justify-content: space-between;
      padding-top: 10px;
      border-top: 1px solid #ddd;
      margin-top: 20px;
    }

    .cart-popup .checkout {
      background: black;
      color: white;
      border: none;
      padding: 10px;
      width: 100%;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s;
    }

    .cart-popup .checkout:hover {
      background: grey;
    }

    .background-blur {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
      z-index: 1000;
      display: none;
    }

    @media (max-width: 600px) {
      .cart-popup {
        width: 95%;
      }
    }

    .fa-trash {
      /* Change the color to red */
      margin-top: 90px;
      /* Adjust the position to your preference */
      cursor: pointer;
      /* Add a pointer cursor */
      transition: color 0.3s;
      /* Add a transition for hover effect */
    }

    .fa-trash:hover {
      color: darkred;
      /* Change color on hover */
    }

    .price-wrapper {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .price-wrapper span {
      color: #000;
      /* Default color for the price */
      font-size: 16px;
      /* Adjust as needed */
      margin-right: 10px;
      /* Adjust spacing */
    }

    .price-wrapper .fa-trash {
      color: black;
      /* Change the color to red */
      margin-left: 10px;
      /* Adjust the position to your preference */
      cursor: pointer;
      /* Add a pointer cursor */
      transition: color 0.3s;
      /* Add a transition for hover effect */
    }

    .price-wrapper .fa-trash:hover {
      color: darkred;
      /* Change color on hover */
    }

    .product-details span {
      color: #000;
      /* Default color for the product name */
      font-size: 16px;
      /* Adjust as needed */
      margin-bottom: 80px;
      /* Add margin for spacing if needed */
      display: block;
      /* Ensure it takes its own line if necessary */
      font-weight: 900;
    }
  </style>
</head>

<body>
  <?php
  include('../connection.php');
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'update_quantity') {
      $product_id = $_POST['product_id'];
      $new_quantity = $_POST['quantity'];
      $sql = "UPDATE cart_item SET quantity = $new_quantity
              WHERE product_id =  $product_id 
              AND cart_id IN (SELECT cart_id FROM cart WHERE user_id = $user_id)";
      $stmt = oci_parse($connection, $sql);
      oci_execute($stmt);
    }
    if ($_POST['action'] == 'delete_item') {
      $product_id = $_POST['product_id'];
      $sql = "DELETE FROM cart_item 
              WHERE product_id = $product_id
              AND cart_id IN (SELECT cart_id FROM cart WHERE user_id = $user_id)";
      $stmt = oci_parse($connection, $sql);
      oci_execute($stmt);
    }
  }
  ?>
  <?php
  function getShopNames($connection)
  {
    $sql = "SELECT s.SHOP_ID, s.SHOP_NAME FROM SHOP s inner join trader t on t.user_id = s.user_id where t.status = '1' ";
    $stmt = oci_parse($connection, $sql);
    if (!oci_execute($stmt)) {
      $e = oci_error($stmt);
      echo "Error executing query: " . $e['message'];
      return [];
    }
    $shops = [];
    while ($row = oci_fetch_assoc($stmt)) {
      $shops[] = $row;
    }
    return $shops;
  }
  $shops = getShopNames($connection);
  ?>
  <nav class="navbar">
    <div class="logo"><a href="../index.php">
        <img src="../Images/Logo/just_logo.png" alt="CleckShopHub"></a>
    </div>
    <div class="toggle-button">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </div>
    <div class="menu">
      <div class="search-bar">
        <form action="" method="post">
          <input type="text" name="search" placeholder="Search..." onkeypress="if(event.key === 'Enter') { this.form.submit(); }">
          <button type="submit" class="search-button"><i class="fa fa-search"></i></button>
        </form>
      </div>
      <!-- <div class="notification-icon">
        <a href="#"><i class="fas fa-bell"></i><span class="label noti">Notifications</span></a>
        <div id="notification-dropdown">
          <a href="#">Notification 1</a>
          <a href="#">Notification 2</a>
        </div>
      </div> -->
      <div class="user-icon">
        <a href="#"><i class="fas fa-user"></i><span class="label">User</span></a>
        <div id="dropdown-menu" class="ddmenu">
          <a href="../userprofile/userprofile.php">Profile</a>
          <a href="../logout/logout.php">Logout</a>
        </div>
      </div>
      <a href="#" class="icon cart-icon" onclick="toggleCartPopup(event)"><i class="fas fa-shopping-cart"></i><span class="label">Cart</span></a>
    </div>
  </nav>

  <div class="sub-navbar">
    <a href="../index.php">Home</a>
    <div class="user-icon">
      <a href="#">Shop</a>
      <div class="dropdown-menu" id="dropdown-menu">
        <?php foreach ($shops as $shop) : ?>
          <a href="../shops/shoppage.php?shop_id=<?php echo urlencode($shop['SHOP_ID']); ?>"><?php echo htmlspecialchars($shop['SHOP_NAME']); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <a href="../contactus/contactus.php">Contact us</a>
    <a href="../aboutus/aboutus.php">About us</a>
  </div>

  <div class="background-blur" id="backgroundBlur">

    <div class="cart-popup" id="cartPopup">
      <h2>
        Your Cart
        <span class="close-btn" onclick="toggleCartPopup()">×</span>
      </h2>
      <form method="post"><button type="submit" name="clear_cart" class="clear-cart-btn" onclick="clearCart()">Clear Cart</button></form>
      <div class="cart-items" id="cartItems">
        <?php
        $user_id = $_SESSION['userID'];
        $sql = "select p.product_id,p.name,p.image,p.price, ci.quantity, p.max_order
          from cart c
          inner join cart_item ci on ci.cart_id = c.cart_id
          inner join product p on p.product_id = ci.product_id
          where c.user_id = '$user_id' ";
        $stid = oci_parse($connection, $sql);
        oci_execute($stid);
        $total = 0;
        while ($row = oci_fetch_assoc($stid)) {
          $product_id = $row['PRODUCT_ID'];
          $name = $row['NAME'];
          $image = $row['IMAGE'];
          $price = $row['PRICE'];
          $quantity = $row['QUANTITY'];
          $max_order = $row['MAX_ORDER'];
          $calc = $price * $quantity;
          $total += $calc;

          echo "<div class=\"cart-item\" data-product-id=\"$product_id\" data-max-order=\"$max_order\">
            <div class=\"quantity-wrapper\">
              <img src=\"../traderdashboard/productsImages/$image\" alt=\"Product\" style=\"width:80px; height:60px;\">
              <div class=\"quantity-controls\">
                <button type=\"button\" onclick=\"decreaseQuantity(this)\">-</button>
                <span>$quantity</span>
                <button type=\"button\" onclick=\"increaseQuantity(this)\">+</button>
              </div>
            </div>
            <div class=\"product-details\">
              <span>$name</span>
            </div>
            <div class=\"price-wrapper\">
              <span>£ $price</span> <span>per unit</span>
              <i class=\"fas fa-trash\" onclick=\"removeItem(this)\"></i>
            </div>
          </div>";
        }
        ?>
      </div>
      <?php
      echo "<div class=\"subtotal\">
      <span>Sub-Total:</span>
      <span id=\"subtotal\">$total</span>
    </div>";
      ?>
      <form method='post'><button type="submit" class="checkout" name="checkout">Checkout</button></form>
    </div>
  </div>
  <?php
  if (isset($_POST['checkout'])) {
    $sql = "select count(*) from cart_item where cart_id in (select cart_id from cart where user_id = '$user_id')";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $count = 0;
    if ($row = oci_fetch_assoc($stid)) {
      $count = $row['COUNT(*)'];
    }
    if ($count == 0) {
      echo "<script>alert('Your cart is empty!!!');</script>";
      // exit;
    } else {
      echo "<script>window.location.href = '../inc/orderpage.php';</script>";
    }
  }
  ?>
  <?php
  if (isset($_POST['clear_cart'])) {

    $sql = "delete from cart_item where cart_id in (select cart_id from cart where user_id = '$user_id')";
    $stid = oci_parse($connection, $sql);
    $exe = oci_execute($stid);
    if (!$exe) {
      echo "<script>alert(Error: 'oci_error($stid)')</script>";
    } else {
      echo "<script>
      const currentUrl = window.location.href;
      const newUrl = currentUrl.slice(0, -1);
      window.location.href = newUrl;
      </script>";
    }
  }
  ?>

  <script>
    function toggleCartPopup() {
      const cartPopup = document.getElementById('cartPopup');
      const backgroundBlur = document.getElementById('backgroundBlur');
      const isClosing = cartPopup.style.display !== 'none';

      cartPopup.style.display = isClosing ? 'none' : 'block';
      backgroundBlur.style.display = isClosing ? 'none' : 'block';

      if (isClosing) {
        const currentUrl = window.location.href;
        const newUrl = currentUrl.slice(0, -1);
        window.location.href = newUrl;
      }
    }

    function increaseQuantity(button) {
      const quantityElement = button.previousElementSibling;
      let quantity = parseInt(quantityElement.textContent, 10);
      const cartItem = button.closest('.cart-item');
      const maxOrder = parseInt(cartItem.dataset.maxOrder, 10);

      if (quantity < maxOrder) {
        quantity++;
        quantityElement.textContent = quantity;
        updateQuantity(cartItem.dataset.productId, quantity);
      } else {
        alert(`You can order a maximum of ${maxOrder} units for this product.`);
      }
    }

    function decreaseQuantity(button) {
      const quantityElement = button.nextElementSibling;
      const cartItem = button.closest('.cart-item');
      let quantity = parseInt(quantityElement.textContent, 10);
      if (quantity > 1) {
        quantity--;
        quantityElement.textContent = quantity;
        updateQuantity(cartItem.dataset.productId, quantity);
      }
    }


    function updateQuantity(productId, quantity) {
      console.log('test');
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          console.log('Quantity updated');
          updateSubtotal();
        }
      };
      xhr.send('action=update_quantity&product_id=' + productId + '&quantity=' + quantity);
    }

    function clearCart() {
      const cartItems = document.getElementById('cartItems');
      cartItems.innerHTML = '';
      updateSubtotal();
    }

    function updateSubtotal() {
      const cartItems = document.querySelectorAll('.cart-item');
      let subtotal = 0;
      cartItems.forEach(item => {
        const price = parseFloat(item.querySelector('.price-wrapper span').textContent.replace('£ ', ''));
        const quantity = parseInt(item.querySelector('.quantity-controls span').textContent, 10);
        subtotal += price * quantity;
      });
      document.getElementById('subtotal').textContent = `£ ${subtotal.toFixed(2)}`;
    }

    function removeItem(trashIcon) {
      const cartItem = trashIcon.closest('.cart-item');
      const productId = cartItem.dataset.productId;
      cartItem.remove();
      updateSubtotal();

      const xhr = new XMLHttpRequest();
      xhr.open('POST', '', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          console.log('Item removed');
        }
      };
      xhr.send('action=delete_item&product_id=' + productId);
    }


    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('cartPopup').style.display = 'none';
      document.getElementById('backgroundBlur').style.display = 'none';
    });

    document.querySelector('.toggle-button').addEventListener('click', function() {
      document.querySelector('.menu').classList.toggle('active');
    });
  </script>
</body>

</html>