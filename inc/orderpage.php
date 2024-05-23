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
    $slots = [];
    while ($row = oci_fetch_assoc($stid)) {
        $slots[] = $row;
    }
    ?>
    <div class="container">
        <h2>Please choose your collection time</h2>

        <form action="<?php echo $paypalURL; ?>" method="post">
            <div class="row">
                <div class="order-summary">
                    <h2>Order Summary</h2><br>

                    <input type="hidden" name="business" value="<?php echo $paypalID; ?>">
                    <input type="hidden" name="cmd" value="_cart">
                    <input type="hidden" name="upload" value="1">
                    <input type="hidden" name="currency_code" value="GBP">

                    <table style="border-collapse: collapse; width: 100%;">
                        <thead>
                            <tr style="background-color:#2b2f33; color: white;">
                                <th style="padding: 8px; border-bottom: 1px solid #ddd;">S.N</th>
                                <th style="padding: 8px; border-bottom: 1px solid #ddd;">Product</th>
                                <th style="padding: 8px; border-bottom: 1px solid #ddd;">Price</th>
                                <th style="padding: 8px; border-bottom: 1px solid #ddd;">Quantity</th>
                                <th style="padding: 8px; border-bottom: 1px solid #ddd;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "select p.product_id, p.name, p.image, p.price, ci.quantity, p.max_order
                                    from cart c
                                    inner join cart_item ci on ci.cart_id = c.cart_id
                                    inner join product p on p.product_id = ci.product_id
                                    where c.user_id = '$user_id'";

                            $stid = oci_parse($connection, $sql);
                            oci_execute($stid);
                            $sn = 1;
                            $total = 0;
                            while ($row = oci_fetch_assoc($stid)) {
                                $name = htmlspecialchars($row['NAME'], ENT_QUOTES, 'UTF-8');
                                $item_number = htmlspecialchars($row['PRODUCT_ID'], ENT_QUOTES, 'UTF-8');
                                $amount = htmlspecialchars($row['PRICE'], ENT_QUOTES, 'UTF-8');
                                $quantity = htmlspecialchars($row['QUANTITY'], ENT_QUOTES, 'UTF-8');
                                $sub_total = $amount * $quantity;
                                $total += $sub_total;

                                echo "<tr style='background-color: #f2f2f2;'>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>" . $sn . "</td>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>" . $name . "</td>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>£ " . $amount . "</td>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>" . $quantity . "</td>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>£ " . $sub_total . "</td>";
                                echo "</tr>";

                                // PayPal input fields for each item
                                echo "<input type='hidden' name='item_name_{$sn}' value='{$name}'>";
                                echo "<input type='hidden' name='item_number_{$sn}' value='{$item_number}'>";
                                echo "<input type='hidden' name='amount_{$sn}' value='{$amount}'>";
                                echo "<input type='hidden' name='quantity_{$sn}' value='{$quantity}'>";
                                $sn++;
                            }
                            ?>

                            <tr style='background-color: #f2f2f2;'>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>Total</td>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'></td>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'></td>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'></td>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>£ <?php echo $total ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Specify URLs -->
                    <input type='hidden' name='cancel_return' value='http://localhost/paypal_jatin/cancel.php'>
                    <input type='hidden' name='return' value='http://localhost/teamProject/Team-project-frontend/inc/order_confirmation.php'>

                    <div class="terms">
                        <label>
                            <input type="checkbox" id="terms" name="terms" required> 
                            I have read and agree to the website <a href="../terms/terms.php">Terms and Conditions</a>
                        </label>
                    </div>

                    <button type="submit" class="btn">Confirm Order</button>
                </div>

                <div class="collection-slot">
                    <h2>Please choose your collection time</h2>
                    <br>
                    <label for="date">Select Date:</label>
                    <select id="date" name="date">
                        <?php foreach ($slots as $slot) : ?>
                            <option value="<?= htmlspecialchars($slot['SLOT_DATE'], ENT_QUOTES, 'UTF-8'); ?>">
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
                <?php $collection_time = null; ?>
            </div>
        </form>
    </div>
</body>

</html>
