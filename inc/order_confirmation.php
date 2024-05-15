<?php require('../inc/links.php');

    include('../connection.php');
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You For Your Order!</title>
  <style>
    body {
      font-family: sans-serif;
      text-align: center;
    }
    h1 {
      font-size: 30px;
      margin-top: 1px;
    }
    h3{
      padding-top: 1px;
    }
   
    p {
      font-size: 1em;
      line-height: 1.5em;
      margin-left: -30px;
      

    }
    .container{
      border: 3px solid black;
      align-items: center;
      width:700px;
      height:335px;
      margin: 50px auto;


    }
    .box {
      align-items: center;
      background-color: white;
      width: 500px;
      height: 210px;
      border: 1px solid black;
      padding: 30px 50px;
      margin: 20px auto;
      padding-bottom: 10px;
      
    }
    .button {
      margin-top: 20px;
    }
    .button a {
      display: inline-block;
      background-color: #4caf50;
      color: white;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      font-size: 15px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      margin-top: -50px;
    }
    .button a:hover {
      background-color: rgb(17, 140, 17);
    }
    .order_info{
      text-align: left;
      margin-left: -10px;
    }
    .order_info hr {
      margin-left: -40px; /* Adjust the negative margin to center the line */
      border: 1px solid rgb(149, 147, 147); 
      width: 37.5em;
    }
 
      
    
  </style>
</head>


<body>
  <div class="container">
  <div class="box">
    <h1>Thank You For Your Order!</h1>
    <h3>Please check your email for confirmation.</h3>
    <br>
    <!--Order information-->
    <div class="order_info">
      <p>Order Number: [order number]</p>
      <hr>
      <p>Total Amount: [total amount]</p>
    </div>
    </div>
    <div class="button">
      <a href="customer_dashboard.html">Continue Shopping</a>
    </div>
    </div>
  
</body>
</html>