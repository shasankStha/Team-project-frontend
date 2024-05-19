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

    /* Styles for the overlay */
    .overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(135, 134, 134, 0.5);
      z-index: 9999;
    }

    /* Styles for the review box */
    .review-box {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      border-radius: 10px;
      padding: 20px;
      width: 30%;
      max-height: 80%;
      overflow-y: auto;
      text-align: left;
    }

    .clear_cart {
      font-size: 15px;
      color: #e44c4c;
      cursor: pointer;
      border: 1px solid #686666;
      padding: 5px;
      margin: 0;
      display: flex;
      justify-content: center;
      border-radius: 5px;
      position: absolute;
      left: 20em;
      margin-right: 30px;
      right: 15px;
    }

    .clear_cart h4 {
      text-align: right;
    }

    .counter {
      font-weight: bold;
      margin-top: 5px;
      width: 15%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #d9d9d9;
      border-radius: 5px;
      background-color: #d9d9d9;
      margin-left: 95px;
      margin-bottom: 30px;
    }

    .btn1 {
      width: 30px;
      height: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #202020;
      cursor: pointer;
    }

    .gg-trash {
      color: #e44c4c;
      cursor: pointer;
      text-align: right;
      margin-right: 5px;
      margin-left: 20em;
      margin-bottom: 60px;
      margin-top: -3em;
    }

    .title {
      color: #202020;
      text-align: center;
      padding-bottom: 10px;
      font-size: 20px;
      margin-right: 7em;
      margin-top: 25px;
    }

    .prices {
      text-align: right;
      font-weight: bold;
      margin-right: 25px;
      font-size: 18px;
    }

    .image_box {
      width: 20%;
      text-align: left;
      height: 20%;
      margin-top: 20px;
    }

    .image_box img {
      max-width: 100%;
    }

    .about {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 10px;
    }

    .box {
      margin-top: 50px;
      border: 1px solid black;
      border-radius: 5px;
      width: 100%;
      height: 9em;
    }

    .checkout {
      float: right;
      margin-right: 5%;
      width: 35%;
    }

    .total {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .Subtotal {
      font-weight: 600;
    }

    .total-amount {
      float: right;
    }

    .button {
      margin-top: 8px;
      width: 100%;
      height: 30px;
      border: none;
      background: linear-gradient(to bottom right, #5ed5b9, #01f9ec);
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      margin-right: 5em;
    }

    .close-button {
      color: red;
      cursor: pointer;
      font-size: 30px;
    }

    .exit {
      text-align: right;
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
  </style>
</head>

<body>
  <?php
  // connection.php already includes the database connection setup
  include('../connection.php');

  function getShopNames($connection)
  {
    $sql = 'SELECT SHOP_ID, SHOP_NAME FROM SHOP';
    $stmt = oci_parse($connection, $sql);
    if (!oci_execute($stmt)) {
      $e = oci_error($stmt);
      echo "Error executing query: " . $e['message'];
      return [];
    }
    $shops = [];
    while ($row = oci_fetch_assoc($stmt)) {
      $shops[] = $row;  // Store the entire row
    }
    return $shops;
  }
  ?>
  <?php
  // Fetch shop names from the database
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
        <input type="text" placeholder="Search...">
        <button class="search-button"><i class="fa fa-search"></i></button>
      </div>
      <div class="notification-icon">
        <a href="#"><i class="fas fa-bell"></i><span class="label noti">Notifications</span></a>
        <div id="notification-dropdown">
          <a href="#">Notification 1</a>
          <a href="#">Notification 2</a>
        </div>
      </div>
      <div class="user-icon">
        <a href="#"><i class="fas fa-user"></i><span class="label">User</span></a>
        <div id="dropdown-menu">
          <a href="../userprofile/userprofile.php">Profile</a>
          <a href="../logout/logout.php">Logout</a>
        </div>
      </div>
      <a href="#" class="icon cart-icon" onclick="toggleCartPopup(event)"><i class="fas fa-shopping-cart"></i><span class="label">Cart</span></a>
    </div>
  </nav>

  <!-- Overlay for Cart Popup -->
  <div class="overlay" onclick="closeCartPopup()">
    <div class="review-box" onclick="stopPropagation(event)">
      <div class="exit">
        <span class="close-button" onclick="closeCartPopup()">&times;</span>
      </div>
      <div class="cart_summary">
        <h1>Your Cart</h1>
        <h4 class="clear_cart">Clear cart</h4>
      </div>
      <div class="box">
        <div class="about">
          <div class="image_box">
            <img src="apple_juice.jpg">
          </div>
          <div>
            <b>
              <div class="title">Product Name</div>
            </b>
            <div class="prices">$3.99</div>
          </div>
        </div>
        <div class="counter">
          <div class="btn1" onclick="updateCount(this, -1)">-</div>
          <div class="count">1</div>
          <div class="btn1" onclick="updateCount(this, 1)">+</div>
        </div>
        <i class="gg-trash" onclick="removeItem(event)"></i>
      </div>
      <div class="box">
        <div class="about">
          <div class="image_box">
            <img src="apple_juice.jpg">
          </div>
          <div>
            <b>
              <div class="title">Product Name</div>
            </b>
            <div class="prices">$2.99</div>
          </div>
        </div>
        <div class="counter">
          <div class="btn1" onclick="updateCount(this, -1)">-</div>
          <div class="count">2</div>
          <div class="btn1" onclick="updateCount(this, 1)">+</div>
        </div>
        <i class="gg-trash" onclick="removeItem(event)"></i>
      </div>
      <hr>
      <div class="checkout">
        <div class="total">
          <div class="Subtotal">Sub-Total:</div>
          <div class="total-amount">$0.00</div>
        </div>
        <a href="order_confirmation.php"><button class="button">Checkout</button></a>
      </div>
    </div>
  </div>

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

  <script>
    // Function to toggle the popup
    function toggleCartPopup(event) {
      var overlay = document.querySelector('.overlay');
      overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';
      event.stopPropagation(); // Prevent click event from propagating to overlay
    }

    // Function to close the popup
    function closeCartPopup() {
      var overlay = document.querySelector('.overlay');
      overlay.style.display = 'none';
    }

    // Function to stop event propagation
    function stopPropagation(event) {
      event.stopPropagation();
    }

    // Function to update the count
    function updateCount(button, increment) {
      const counter = button.closest('.counter');
      const countElement = counter.querySelector('.count');
      let currentCount = parseInt(countElement.textContent);

      // Update the count based on the increment value (+1 for increase, -1 for decrease)
      currentCount += increment;

      // Ensure the count does not go below zero (optional, depending on your use case)
      if (currentCount < 1) {
        const userConfirmed = confirm("Are you sure you want to delete this item from your cart?");
        // If the user confirms the deletion, proceed with removing the item
        if (userConfirmed) {
          const itemBox = button.closest('.box');
          if (itemBox) {
            itemBox.remove();
          }
        }
      } else {
        // Update the count element with the new count
        countElement.textContent = currentCount;
      }

      // Recalculate the total amount
      calculateTotal();
    }

    // Function to remove an item from the cart
    function removeItem(event) {
      // Prevent the event from propagating further (if necessary)
      event.stopPropagation();

      // Show a confirmation dialog to the user
      const userConfirmed = confirm("Are you sure you want to delete this item from your cart?");

      // If the user confirms the deletion, proceed with removing the item
      if (userConfirmed) {
        const trashIcon = event.target;
        const itemBox = trashIcon.closest('.box');
        if (itemBox) {
          itemBox.remove();
        }
      }

      // Recalculate the total amount
      calculateTotal();
    }

    // Function to clear the cart
    function clearCart() {
      // Select all 'box' elements (representing each cart item)
      const cartItems = document.querySelectorAll('.box');

      // Show a confirmation dialog to the user if the cart is not empty
      if (cartItems.length > 0) {
        const userConfirmed = confirm("Are you sure you want to clear all items from your cart?");
        // If the user confirms, proceed with removing all items
        if (!userConfirmed) {
          return; // Exit the function if the user cancels
        }
      }

      // Iterate over each cart item and remove it from the DOM
      cartItems.forEach(item => {
        item.remove();
      });

      // If there are no items left in the cart, show a message
      const cartIsEmpty = document.querySelectorAll('.box').length === 0;
      if (cartIsEmpty) {
        alert("Your cart is empty.");
      }

      // Recalculate the total amount
      calculateTotal();
    }

    // Function to calculate the total amount
    function calculateTotal() {
      let subtotal = 0;

      // Get all cart items
      const cartItems = document.querySelectorAll('.box');
      const subtotalElement = document.querySelector('.total-amount');

      cartItems.forEach(item => {
        const countElement = item.querySelector('.count');
        const priceElement = item.querySelector('.prices');

        const count = parseInt(countElement.textContent);
        const priceText = priceElement.textContent.trim();
        const price = parseFloat(priceText.replace('$', ''));

        subtotal += count * price;
      });

      // Update the subtotal element with the calculated total
      subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    }

    // Attach event listeners to all counter elements
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
      const decreaseButton = counter.querySelector('.btn1:nth-child(1)');
      const increaseButton = counter.querySelector('.btn1:nth-child(3)');

      // Attach event listeners to the decrease and increase buttons
      decreaseButton.addEventListener('click', function() {
        updateCount(this, -1);
      });

      increaseButton.addEventListener('click', function() {
        updateCount(this, 1);
      });
    });

    // Attach event listeners to all trash bin icons
    const trashIcons = document.querySelectorAll('.gg-trash');
    trashIcons.forEach(trashIcon => {
      trashIcon.addEventListener('click', removeItem);
    });

    // Attach an event listener to the "Clear cart" element
    const clearCartElement = document.querySelector('.clear_cart');
    if (clearCartElement) {
      clearCartElement.addEventListener('click', clearCart);
    }

    // Initialize total amount on page load
    calculateTotal();

    // Function to toggle the menu for the hamburger button
    document.querySelector('.toggle-button').addEventListener('click', function() {
      document.querySelector('.menu').classList.toggle('active');
    });
  </script>
</body>

</html>