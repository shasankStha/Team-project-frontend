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
    <div class="container">


        <form method="post">
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
                                <th style="padding: 8px; border-bottom: 1px solid #ddd;">Discount Amount</th>
                                <th style="padding: 12px; border-bottom: 1px solid #ddd;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "select p.product_id, p.name, p.image, p.price, ci.quantity, p.max_order, p.discount
                                    from cart c
                                    inner join cart_item ci on ci.cart_id = c.cart_id
                                    inner join product p on p.product_id = ci.product_id
                                    where c.user_id = '$user_id'";

                            $stid = oci_parse($connection, $sql);
                            oci_execute($stid);
                            $sn = 1;
                            $total = 0;
                            while ($row = oci_fetch_assoc($stid)) {
                                $name = $row['NAME'];
                                $item_number = $row['PRODUCT_ID'];
                                $amount = $row['PRICE'];
                                $quantity = $row['QUANTITY'];
                                $discount = $row['DISCOUNT'];
                                $sub = $amount * $quantity;
                                $dis_amt = ($sub * $discount) / 100;
                                $sub_total = $sub - $dis_amt;
                                $total += $sub_total;

                                echo "<tr style='background-color: #f2f2f2;'>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>" . $sn . "</td>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>" . $name . "</td>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>£ " . $amount . "</td>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>" . $quantity . "</td>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>" . $dis_amt . "</td>";
                                echo "<td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>£ " . $sub_total . "</td>";
                                echo "</tr>";
                                $sn++;
                            }
                            ?>

                            <tr style='background-color: #f2f2f2;'>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>Total</td>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'></td>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'></td>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'></td>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'></td>
                                <td style='padding: 8px; border-bottom: 1px solid #ddd; text-align: center'>£ <?php echo $total ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>

                <div class="collection-slot">
                    <h2>Please choose your collection time</h2>
                    <br>
                    <label for="date">Select Date:</label>
                    <select id="date" name="date">
                        <?php
                        $sql = "select slot_date from collection_slot where slot_date>(select sysdate+1 from dual) group by slot_date order by slot_date";
                        $stid = oci_parse($connection, $sql);
                        oci_execute($stid);
                        $slots = [];
                        while ($row = oci_fetch_assoc($stid)) {
                            $slots[] = $row;
                        }
                        foreach ($slots as $slot) : ?>
                            <option value="<?= htmlspecialchars($slot['SLOT_DATE'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?= date('d-M-Y', strtotime($slot['SLOT_DATE'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="collection-time">
                        <h3>Collection Time:</h3><br>

                        <label><input type="radio" name="collection_time" value="10:00" required> 10am - 1pm</label><br>
                        <label><input type="radio" name="collection_time" value="13:00" required> 1pm - 4pm</label><br>
                        <label><input type="radio" name="collection_time" value="16:00" required> 4pm - 7pm</label>
                    </div>
                </div>
                <?php $collection_time = null; ?>

            </div>

            <!-- Specify URLs -->
            <input type='hidden' name='cancel_return' value='http://localhost/paypal_jatin/cancel.php'>
            <input type='hidden' name='return' value='http://localhost/teamProject/Team-project-frontend/inc/order_confirmation.php'>

            <div class="terms">
                <label>
                    <input type="checkbox" id="terms" name="terms" required>
                    I have read and agree to the website <a href="../terms/terms.php">Terms and Conditions</a>
                </label>
            </div>

            <button name='submit' type="submit" class="btn">Confirm Order</button>
        </form>
    </div>
</body>

<?php
if (isset($_POST['submit'])) {
    $collection_time = $_POST['collection_time'];
    $date = $_POST['date'];
    $sql = "select count(*) from \"ORDER\" where collection_slot_id in (select collection_slot_id from collection_slot where slot_date = '$date' and start_time = '$collection_time')";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $count = null;
    if ($row = oci_fetch_assoc($stid)) {
        $count = $row["COUNT(*)"];
    }
    if ($count == 20) {
        echo "<script>alert('Collection slot is full!!! Select another slot.')</script>";
        exit;
    }

    $sql = "select collection_slot_id from collection_slot where slot_date = '$date' and start_time = '$collection_time'";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);
    $id = null;
    if ($row = oci_fetch_assoc($stid)) {
        $id = $row["COLLECTION_SLOT_ID"];
    }
    $sql = "insert into \"ORDER\" values(null,sysdate,null,'0','$user_id','$id')";
    $stid = oci_parse($connection, $sql);
    oci_execute($stid);

    $paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    $paypalID = 'sb-p4q6930555431@business.example.com'; //Business Email

?>

    <form id="paypalForm" action="<?php echo $paypalURL; ?>" method="post">
        <input type="hidden" name="business" value="<?php echo $paypalID; ?>">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="upload" value="1">
        <input type="hidden" name="currency_code" value="GBP">
        <?php
        $sn = 1;
        $total = 0;
        $sql = "select p.product_id, p.name, p.image, p.price, ci.quantity, p.max_order, p.discount
                                    from cart c
                                    inner join cart_item ci on ci.cart_id = c.cart_id
                                    inner join product p on p.product_id = ci.product_id
                                    where c.user_id = '$user_id'";

        $stid = oci_parse($connection, $sql);
        oci_execute($stid);
        while ($row = oci_fetch_assoc($stid)) {
            $name = $row['NAME'];
            $item_number = $row['PRODUCT_ID'];
            $amount = $row['PRICE'];
            $quantity = $row['QUANTITY'];
            $discount = $row['DISCOUNT'];
            $amt = $amount - ($amount * $discount) / 100;
            $sub = $amount * $quantity;
            $dis_amt = ($sub * $discount) / 100;
            $sub_total = $sub - $dis_amt;
            $total += $sub_total;

            echo "<input type='hidden' name='item_name_{$sn}' value='{$name}'>";
            echo "<input type='hidden' name='item_number_{$sn}' value='{$item_number}'>";
            echo "<input type='hidden' name='quantity_{$sn}' value='{$quantity}'>";
            echo "<input type='hidden' name='amount_{$sn}' value='{$amt}'>";
            $sn++;
        }
        ?>
        <input type="hidden" name="cancel_return" value="http://localhost/cleckshophub/homepage/homepage.php">
        <input type="hidden" name="return" value="http://localhost/cleckshophub/inc/order_confirmation.php">

        <script>
            document.getElementById("paypalForm").submit();
        </script>
    </form>
<?php
}
?>

</html>