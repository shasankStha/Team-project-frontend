<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('../inc/links.php'); ?>
    <title>User profile</title>
    <link rel="stylesheet" href="../css/userorderhistory.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
    include('../connection.php');
    session_start();

    $isLoggedIn = isset($_SESSION['loggedinUser']) && $_SESSION['loggedinUser'] === TRUE;

    if ($isLoggedIn) {
        include('../inc/loggedin_header.php');
    } else {
        include('../inc/header.php');
    }

    if (!$isLoggedIn || !isset($_SESSION['userID'])) {
        header('Location: ../login/login.php');
        exit;
    }
    $userID = $_SESSION['userID'];
    ?>

    <div class="container">
        <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
        <div class="sidebar" id="sidebar">
            <a href="userprofile.php">Profile</a>
            <a href="userorderhistory.php">Orders History</a>
            <a href="userfavourites.php">Favourites</a>
            <a href="userchangepassword.php">Change Password</a>
            <a href="../logout/logout.php">Log out</a>
        </div>
        <div class="main-content">
            <div class="order-history">
                <h1>Order History</h1>
                <table>
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Order Id</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            $query = "SELECT distinct o.ORDER_ID, o.ORDER_DATE, o.TOTAL_PRICE FROM customer c
                            INNER JOIN \"ORDER\" o on o.user_id=c.user_id
                            INNER JOIN order_item oi on oi.order_id = o.order_id
                            INNER JOIN product p ON p.product_id = oi.product_id
                            WHERE c.user_id = $userID
                            ORDER BY o.ORDER_ID desc";
                            $stid = oci_parse($connection, $query);
                            if (!oci_execute($stid)) {
                                $err = oci_error($stid);
                                echo "<tr><td colspan='5'>Error: " . htmlspecialchars($err['message']) . "</td></tr>";
                            } else {
                                $sn = 1;  // Serial Number counter
                                while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                                    echo "<tr>\n";
                                    echo "    <td>" . htmlspecialchars($sn++) . "</td>\n";
                                    echo "    <td>" . htmlspecialchars($row['ORDER_ID']) . "</td>\n";
                                    echo "    <td>" . htmlspecialchars($row['ORDER_DATE']) . "</td>\n";
                                    echo "    <td>£ " . htmlspecialchars($row['TOTAL_PRICE']) . "</td>\n";
                                    echo "    <td><button type='button' class='btn btn-dark shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#order-modal' data-order-id='" . htmlspecialchars($row['ORDER_ID']) . "'> <i class='bi bi-pencil-square'></i> View order</button></td>\n";
                                    echo "</tr>\n";
                                }
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
                <div class="modal fade" id="order-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel">
                    <div class="modal-dialog" style="max-width: 800px; width: 100%; height: 90vh; max-height: 90vh;">
                        <div class="modal-content" style="height: 100%;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Order</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive" style="height: calc(100% - 50px); overflow-y: scroll; width: 100%;">
                                    <table class="table table-hover border text-center" style="min-width: 100%; width: 100%;">
                                        <thead>
                                            <tr class="bg-dark text-light">
                                                <th scope="col">S.N</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Price per Unit</th>
                                                <th scope="col">Discount</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="order-details">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('../inc/footer.php'); ?>
    <script>
        document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                fetchOrderDetails(orderId);
            });
        });

        function fetchOrderDetails(orderId) {
            fetch(`fetch_order_details.php?order_id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    const orderDetailsContainer = document.getElementById('order-details');
                    orderDetailsContainer.innerHTML = '';
                    let sn = 1;
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${sn++}</td>
                            <td>${row.PRODUCT_NAME}</td>
                            <td>${row.QUANTITY}</td>
                            <td>£ ${row.PRICE_PER_UNIT}</td>
                            <td>${row.DISCOUNT}</td>
                            <td>£ ${row.TOTAL_AMOUNT}</td>
                        `;
                        orderDetailsContainer.appendChild(tr);
                    });
                });
        }

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('expanded');
        }
    </script>
</body>

</html>