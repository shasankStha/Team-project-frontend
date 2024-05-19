<?php  
// require('links.php'); 
?>


<style>
   * {
       font-family: 'Poppins', sans-serif;
   }


   #h-font {
       font-family: 'Merienda', cursive;
   }


   body,
   #navbar {
       margin: 0;
       padding: 0;
   }


   #navbar {
       background-color: black;
       padding: 10px;
       position: relative;
       position: sticky;  
       top: 0;
       left: 0;
       width: 100%;
       z-index: 1000;
       justify-content: space-between;
       align-items: center;
   }
   .navbar-container {
       display: flex;
       justify-content: space-between;
       align-items: center;
       width: 100%;
   }


   #navbar-brand {
       display: flex;
       align-items: center;
       flex-basis: 20%;
       /* Assign space for logo */
   }


   #icon-container {
       justify-content: flex-end;
       margin-right: 20px;
       /* Adjust as necessary for your layout */
       gap: 15px;
   }


   #navigation {
       list-style: none;
       margin: 0;
       padding: 0;
       display: flex;
       gap: 20px;
       /* Adjust the space between navigation items */
       justify-content: flex-end;
       /* Aligns items to the right */
   }


   #navigation li {
       padding: 10px 20px;
   }


   #navigation a {
       color: white;
       /* Set link color to white */
       text-decoration: none;
       transition: all 0.3s ease;
   }


   /* Update link hover effect */
   #navigation a:hover {
       color: #cccccc;
       /* Lighter shade for hover */
       text-decoration: none;
   }
   #hamburger {
       display: none;
       cursor: pointer;
       font-size: 2rem;
       color: white;
       position: absolute;
       top: 10px;
       right:30px;
   }
   
     /* Responsive styles */
     @media (max-width: 768px) {
       #navigation {
           flex-direction: column;
           align-items: center;
       }


       #navigation li {
           padding: 10px 0;
       }
   }
   @media (max-width: 768px) {
       #hamburger {
           display: block;
       }


       #navigation {
           display: none;
           flex-direction: column;
           align-items: center;
       }


       /* Show navigation when menu is open */
       #navigation.open {
           display: flex;
       }
   }
   @media (max-width: 768px) {
       .navbar-container {
           flex-direction: column;
       }


       #navbar-brand {
           margin-bottom: 10px;
       }


       /* ...existing styles... */
   }


   /* No changes below this point needed */
</style>


<nav class="navbar shadow-sm" id="navbar">
<!--hamburger -->
<div id="hamburger">&#9776;</div>


   <!-- Logo -->
   <div class="navbar-container">
   <div class="navbar-brand" id="navbar-brand">
       <a href="../index.php">
           <!-- <img src="../images/logo/white.png" alt="Logo" style="height: 50px;"> -->
           <img  id ="formimage" src="../images/logo/white.png" alt="Logo" style="height: 50px;">
       </a>
   </div>


   <!-- Navigation Links -->
   <ul class="navigation" id="navigation">
       <li><a href="../index.php">Home</a></li>
       <li><a href="../aboutus/aboutus.php">About</a></li>
       <li><a href="../contactus/contactus.php">Contact Us</a></li>
   </ul>
</div>
</nav>
<script>
   // Get elements
   var hamburger = document.getElementById('hamburger');
   var navigation = document.getElementById('navigation');


   // Toggle class on click
   hamburger.addEventListener('click', function() {
       navigation.classList.toggle('open');
   });
</script>
