
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
      if (currentCount < 0) {
          currentCount = 0;
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
          // Find the closest 'box' element to the trash bin icon and remove it
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
      // Show a confirmation dialog to the user
      const userConfirmed = confirm("Are you sure you want to clear all items from your cart?");
      
      // If the user confirms, proceed with removing all items
      if (userConfirmed) {
          // Select all 'box' elements (representing each cart item)
          const cartItems = document.querySelectorAll('.box');
          
          // Iterate over each cart item and remove it from the DOM
          cartItems.forEach(item => {
              item.remove();
          });
      }
  }
  
  // Attach an event listener to the "Clear cart" element
  const clearCartElement = document.querySelector('.clear_cart');
  if (clearCartElement) {
      clearCartElement.addEventListener('click', clearCart);
  }
  
    