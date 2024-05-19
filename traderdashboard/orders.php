<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trader Panel - Order History</title>
  <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

  <?php require('traderdashboardheader.php');
  require('../connection.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">ORDER HISTORY</h3>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover border text-center" style="min-width: 1300px;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">S.N</th>
                    <th scope="col">ID</th>
                    <th scope="col">Date</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="">
                  <?php
                  $query = "SELECT ORDER_ID, ORDER_DATE, TOTAL_PRICE FROM \"ORDER\"";
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
                      echo "    <td>" . htmlspecialchars($row['TOTAL_PRICE']) . "</td>\n";
                      echo "    <td><button type='button' class='btn btn-dark shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#order-modal' data-order-id='" . htmlspecialchars($row['ORDER_ID']) . "'> <i class='bi bi-pencil-square'></i> View order</button></td>\n";
                      echo "</tr>\n";
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
            <div class="modal fade" id="order-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel">
              <div class="modal-dialog" style="max-width: 90%; width: 90%;">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="table-responsive">
                      <table class="table table-hover border text-center" style="min-width: 1500px;">
                        <thead>
                          <tr class="bg-dark text-light">
                            <th scope="col">S.N</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price per Unit</th>
                            <th scope="col">Discount</th>
                            <th scope="col">Total Amount</th>
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
    </div>
  </div>

  <script>
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
      button.addEventListener('click', function () {
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
              <td>${row.PRICE_PER_UNIT}</td>
              <td>${row.DISCOUNT}</td>
              <td>${row.TOTAL_AMOUNT}</td>
            `;
            orderDetailsContainer.appendChild(tr);
          });
        });
    }
  </script>

</body>

</html>
