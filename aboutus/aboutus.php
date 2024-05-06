<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <link rel="stylesheet" href="../css/aboutus.css">

</head>

<body>
  <?php
  session_start();

  $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

  if ($isLoggedIn) {
    include('../inc/loggedin_header.php');
  } else {
    include('../inc/header.php');
  }

  ?>
  <header class="header">
    <h1>About Us</h1>
    <p>Dedicated to providing your family with the freshest organic produce, straight from our local farms to your table.</p>
  </header>

  <section class="main-content">
    <div class="image-block">
      <img src="../images/p1.jpg" alt="Basket full of various fresh produce.">
    </div>
    <div class="text-block">
      <div class="text-container">
        <h2>Our Philosophy</h2>
        <p>
          In a world where the pace of life seems to accelerate by the day, CleckShopHub stands as a bastion of mindfulness and sustainability. Our philosophy is simple yet profound: to reconnect people with the natural bounty of our planet through the food they eat. We believe that the act of choosing organic is not just a personal health decision but a collective step towards a more sustainable and ethical world.

          Every product in our catalog is a testament to this belief. From crisp, vibrant organic vegetables to ethically-raised meat, each item is selected for its quality, freshness, and ecological footprint. By fostering close relationships with organic farmers and producers, we ensure that our products are grown and harvested in ways that benefit the earth, support local economies, and promote animal welfare.




        </p>
      </div>
    </div>
  </section>

  <section class="main-content">
    <div class="image-block">
      <img src="../images/p2.jpg" alt="Fresh vegetables on a wooden table.">
    </div>
    <div class="text-block">
      <div class="text-container">
        <h2>Our Community Impact</h2>
        <p>
          At CleckShopHub, we believe in the power of community. Our impact reaches far beyond the simple act of selling organic products; it fosters a vibrant ecosystem of sustainability, education, and support. By connecting consumers directly with local organic farmers, we not only ensure a fresher, healthier food supply but also strengthen local economies and support sustainable agricultural practices. Our platform serves as an educational hub, offering insights into the benefits of organic living and inspiring our community to make choices that positively affect their health and the environment. Through CleckShopHub, every purchase becomes a part of a larger movement towards a more sustainable and equitable world, proving that together, we can create meaningful change for our planet and future generations.
        </p>
      </div>
    </div>
  </section>
  <?php require('../inc/footer.php'); ?>
</body>

</html>