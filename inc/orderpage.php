<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    session_start();
    include('../connection.php');
    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;
    $user_id = null;
    if ($isLoggedIn) {
        $user_id = $_SESSION['userID'];
        require('loggedin_header.php');
    } else {
        require('inc/header.php');
    }
    ?>
    <?php
    $paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    $paypalID = 'sb-l3squ30556079@business.example.com'; //Business Email
    ?>

    <?php
    $sql = "select slot_date from collection_slot where slot_date>(select sysdate+1 from dual) group by slot_date";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $date = null;
    $start = null;
    $end = null;
    $slots = [];
    while ($row = oci_fetch_assoc($stid)) {
        $slots[] = $row;
    }



    ?>
    <div class="container">
        <h2>Please choose your collection time</h2>
        <form method="POST">
            <div class="row">
                <div class="collection-slot">
                    <h2>Collection Slot</h2><br>
                    <label for="date">Select Date:</label>
                    <select id="date" name="date">
                        <?php foreach ($slots as $slot) : ?>
                            <option value="<?= $slot['SLOT_DATE']; ?>">
                                <?= date('d-M-Y', strtotime($slot['SLOT_DATE'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="collection-time">
                        <h3>Collection Time:</h3><br>
                        <label><input type="radio" name="collection_time" value="10am-1pm"> 10am - 1pm</label><br>
                        <label><input type="radio" name="collection_time" value="1pm-4pm"> 1pm - 4pm</label><br>
                        <label><input type="radio" name="collection_time" value="4pm-7pm"> 4pm - 7pm</label>
                    </div>
                </div>
                <?php
                $collection_time = null;
                ?>

                <!-- <div class="order-summary">
                    <h2>Order Summary</h2><br>
                    <label>Subtotal: £<span id="subtotal">300</span></label><br>

                    <div class="terms">
                        <label><input type="checkbox" id="terms" name="terms"> I have read and agree to the website <a href="../terms/terms.php">Terms and Conditions</a></label>
                    </div>

                    <button type="submit" class="btn" value = "confirmBtn">Confirm Order</button>
                </div>
            </div> -->
        </form>

        <?php
        $sql = "select p.product_id,p.name,p.image,p.price, ci.quantity, p.max_order
        from cart c
        inner join cart_item ci on ci.cart_id = c.cart_id
        inner join product p on p.product_id = ci.product_id
        where c.user_id = '$user_id'";

        $stid = oci_parse($connection, $sql);
        oci_execute($stid);
        ?>

        <form action="<?php echo $paypalURL; ?>" method="post">
            <input type="hidden" name="business" value="<?php echo $paypalID; ?>">
            <!-- Specify a Buy Now button. -->
            <input type="hidden" name="cmd" value="_xclick">

            <?php
            while ($row = oci_fetch_assoc($stid)) {
                // Item details
                $item_name = $row['NAME'];
                $item_number = $row['PRODUCT_ID'];
                $amount = $row['PRICE'];
                $quantity = $row['QUANTITY'];
            ?>

                <!-- Input fields for each item -->
                <input type="hidden" name="item_name" value="<?php echo $item_name; ?>">
                <input type="hidden" name="item_number" value="<?php echo $item_number; ?>">
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                <input type="hidden" name="currency_code" value="GBP">
                <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">

            <?php } ?>

            <!-- Specify URLs -->
            <input type='hidden' name='cancel_return' value='http://localhost/paypal_jatin/cancel.php'>
            <input type='hidden' name='return' value='http://localhost/paypal_jatin/success.php'>

            <!-- Display the payment button. -->
            <input type="image" name="submit" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
            <img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
        </form>


    </div>
</body>

</html>