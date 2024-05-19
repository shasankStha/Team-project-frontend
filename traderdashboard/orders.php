<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trader Panel - Order History</title>
  <?php require ('inc/links.php'); ?>
</head>

<body class="bg-light">

  <?php require ('traderdashboardheader.php');
  require ('../connection.php'); ?>

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
                  oci_execute($stid);

                  $sn = 1;  // Serial Number counter
                  while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    echo "<tr>\n";
                    echo "    <td>" . htmlspecialchars($sn++) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($row['ORDER_ID']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($row['ORDER_DATE']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($row['TOTAL_PRICE']) . "</td>\n";
                    echo "    <td><button type='button' class='btn btn-dark shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-modal'> <i class='bi bi-pencil-square'></i> View order</button></td>\n";
                    echo "</tr>\n";
                  }
                  ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>



</body>

</html>