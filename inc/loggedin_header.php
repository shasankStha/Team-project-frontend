<?php require('../inc/links.php');
?>
<link href='https://unpkg.com/css.gg@2.0.0/icons/css/trash.css' rel='stylesheet'>
<script src="script.js" defer></script>
<style>
  * {
    font-family: 'Poppins', sans-serif;
  }

  .h-font {
    font-family: 'Merienda', cursive;
  }

  body {
    margin: 0;
    padding: 0;
  }

  .navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: white;
    padding: 10px;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
  }

  .navbar-brand {
    display: flex;
    align-items: center;
  }

  .login a::after {
    content: "";
    width: 0%;
    height: 2px;
    display: block;
    background-color: orange;
    margin: auto;
    transition: 0.3s;
  }

  .login a:hover::after {
    width: 100%;
  }

  .sub-navbar {
    display: flex;
    justify-content: space-around;
    background-color: #2b2f33;
    /* Dark background for sub-navbar */
    color: white;
    /* Change text color to white */
    padding: 10px 0;
    margin-bottom: 20px;
  }

  .sub-navbar a {
    color: white;
    /* Change link color to white */
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    margin: 0 5px;
    padding: 8px 16px;
  }

  .sub-navbar a:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
    background-color: #f0f0f0;
    color: black;
    /* Text color on hover */
    border-radius: 30px;
  }

  .icon-container a:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
    background-color: #f0f0f0;
    color: black;
    /* Text color on hover */
    border-radius: 30px;
  }

  .search-container {
    display: flex;
    justify-content: center;
    flex-grow: 1;
    margin-right: -100px;
    margin-left: 15px;
  }

  .search-bar {
    width: 50%;
    padding: 8px;
    border: 1px solid black;
    border-radius: 20px;
  }

  .icon-container {
    display: flex;
    align-items: center;
    gap: 15px;
    /* Revert icon colors to black */
    color: black;
    /* Ensure anchor tag inherits black color for icons */
  }

  /* Specify the styles for the icons so they appear black */
  .icon-container img {
    filter: none;
    height: 24px;
    width: 24px;
  }

  .icon-container .icon {
    padding-right: 20px;
  }

  .icon-container .cart-icon {
    margin-left: 20px;
  }

  .dropdown-menu {
    display: flex;
    flex-direction: row;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
  }

  .dropdown-menu a {
    color: black;
    /* Text color for dropdown links */
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }

  .user-icon:hover .dropdown-menu {
    display: block;
  }

  .user-icon .dropdown-menu {
    background-color: #2b2f33;
    /* Match the sub-navbar background */
  }

  .user-icon:hover .dropdown-menu a {
    color: white;
    /* White text for dropdown items on hover */
  }

  /*-----------for cart----------*/

  body {
    font-family: sans-serif;
    margin: 0;
    padding: 0;
  }

  main {
    padding: 20px;
  }

  header {
    background-color: #f0f0f0;
    text-align: center;
  }

  main {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .cart-summary {
    flex-basis: 45%;
    background-color: #fff;
    padding: 10px;
    border: 1px solid #ddd;
    margin: 10px;
  }

  h1 {
    margin-bottom: 10px;
  }

  .cart-summary p {
    font-weight: bold;
    text-align: right;
  }

  button[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
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
    /* Semi-transparent overlay */
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

  .btn {
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
</style>
<div>
  <nav class="navbar" style="display: flex; justify-content:space-between sticky-top;">
    <!-- Logo -->
    <div>
      <a class="navbar-brand" href="../index.php">
        <img src="../Images/logo/just_logo.png" style="height: 50px;">
      </a>
    </div>


    <div class="search_and_icons" style="display: flex; align-items:center;">
      <!-- SearchBox -->
      <div class="search-container" style="width: 900px; padding-left: 300px;">
        <input class="search-bar" type="text" placeholder="Search..." style="border-radius: 20px;">
      </div>

      <!-- Icons -->
      <div class="icon-container">
        <?php
        // Display icons if user is logged in
        echo '<div class="user-icon">
                    <img src="../images/user.png" alt="User" style="width: 24px; height: 24px; border-radius: 50%;">
                    <div class="dropdown-menu">
                        <a href="../userprofile/userprofile.php">Profile</a>
                        <a href="../logout/logout.php">Logout</a>
                    </div>
                </div>';

        echo '<div class="icons" style="display:flex; justify-content:space-evenly; padding-right: 50px;">
                    <a href="notifications.php" class="icon"><img src="../images/notifications.png" alt=""></a>
                    <a href="#" class="icon"><img src="../images/cart1.png" alt="Cart" onclick="togglePopup(event)"></a>
                </div>'
        ?>

      </div>
    </div>
  </nav>
  <!--for cart-->
  <div class="overlay" onclick="closePopup()">
    <div class="review-box" onclick="stopPropagation(event)">

      <div class="exit">
        <div>
          <span class="close-button" onclick="closePopup()">&times;</span>

        </div>
      </div>

      <div class="cart_summary">
        <h1>Your Cart</h1>

        <h4 class="clear_cart">Clear cart</h5>
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
          <div class="btn" onclick="decrementItem(this)">-</div> <!-- Add onclick attribute -->
          <div class="count">3</div>
          <div class="btn">+</div>
        </div>

        <i class="gg-trash"></i>
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
          <div class="btn">-</div>
          <div class="count">2</div>
          <div class="btn">+</div>
        </div>
        <i class="gg-trash"></i>
      </div>



      <hr>
      <div class="checkout">
        <div class="total">
          <div class="Subtotal">Sub-Total:</div>
          <div class="total-amount">$6.18</div>
        </div>

        <a href="order_confirmation.php"><button class="button">Checkout</button></a>
      </div>

    </div>


  </div>

  <!--cart end-->
  <div class="sub-navbar">
    <a href="../index.php">Home</a>
    <div class="user-icon">
      <a href="#">Shop</a>
      <div class="dropdown-menu">
        <a href="../shops/shoppage.php">Fishmonger</a>
        <a href="../shops/shoppage.php">Butcher</a>
        <a href="../shops/shoppage.php">Greengrocer</a>
        <a href="../shops/shoppage.php">Bakery</a>
        <a href="../shops/shoppage.php">Delicatessen</a>
      </div>
    </div>

    <a href="../contactus/contactus.php">Contact us</a>
    <a href="../aboutus/aboutus.php">About us</a>
  </div>
</div>

<script>
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
        const trashIcon = event.target;
        const itemBox = trashIcon.closest('.box');
        if (itemBox) {
          itemBox.remove();
        }
      }
    }

    // Update the count element with the new count
    countElement.textContent = currentCount;
  }

  // Attach event listeners to all counter elements
  const counters = document.querySelectorAll('.counter');
  counters.forEach(counter => {
    const decreaseButton = counter.querySelector('.btn:nth-child(1)');
    const increaseButton = counter.querySelector('.btn:nth-child(3)');

    // Attach event listeners to the decrease and increase buttons
    decreaseButton.addEventListener('click', function() {
      updateCount(this, -1);
    });

    increaseButton.addEventListener('click', function() {
      updateCount(this, 1);
    });
  });

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
  }

  // Attach event listeners to all trash bin icons
  const trashIcons = document.querySelectorAll('.gg-trash');
  trashIcons.forEach(trashIcon => {
    trashIcon.addEventListener('click', removeItem);
  });

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
  }

  // Attach an event listener to the "Clear cart" element
  const clearCartElement = document.querySelector('.clear_cart');
  if (clearCartElement) {
    clearCartElement.addEventListener('click', clearCart);
  }
</script>