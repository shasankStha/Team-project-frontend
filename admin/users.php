<?php
require('../connection.php');  // Include your database connection

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
  $userId = $_GET['id'];

  // // Start transaction
  // oci_execute(oci_parse($connection, 'BEGIN'));
  $error = false;

  // Delete dependent records in the CART table
  // $deleteCartQuery = "DELETE FROM CART WHERE USER_ID = :userId";
  // $deleteCartStmt = oci_parse($connection, $deleteCartQuery);
  // oci_bind_by_name($deleteCartStmt, ":userId", $userId);
  // if (!oci_execute($deleteCartStmt)) {
  //   $error = true;
  // }

  // Delete dependent records in the CUSTOMER table
  $deleteCustomerQuery = "update CUSTOMER set status = '0' WHERE USER_ID = :userId";
  $deleteCustomerStmt = oci_parse($connection, $deleteCustomerQuery);
  oci_bind_by_name($deleteCustomerStmt, ":userId", $userId);
  if (!oci_execute($deleteCustomerStmt)) {
    $error = true;
  }

  // Proceed to delete the user if no errors
  // if (!$error) {
  //   $deleteUserQuery = "DELETE FROM \"USER\" WHERE USER_ID = :userId";
  //   $deleteUserStmt = oci_parse($connection, $deleteUserQuery);
  //   oci_bind_by_name($deleteUserStmt, ":userId", $userId);

  //   if (oci_execute($deleteUserStmt)) {
  //     oci_execute(oci_parse($connection, 'COMMIT'));
  //     $message = "User and all associated records deleted successfully.";
  //   } else {
  //     $error = true;
  //   }
  // }

  if ($error) {
    oci_execute(oci_parse($connection, 'ROLLBACK'));
    $e = oci_error();
    $message = "Error deleting user and associated records: " . $e['message'];
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - Users</title>
  <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

  <?php require('admindashboardheader.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">USERS</h3>
        <?php if (!empty($message))
          echo "<p>$message</p>"; ?> <!-- Display message -->

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover border text-center" style="min-width: 1000px; width : 100%;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">S.N</th>
                    <th scope="col">Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone no.</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="users-data">
                  <?php
                  $query = "SELECT u.USER_ID, u.USERNAME, u.EMAIL, u.FIRST_NAME, u.LAST_NAME, u.CONTACT_NUMBER, u.ROLE 
                  FROM \"USER\" u
                  left join customer c on c.user_id = u.user_id
                  where (u.ROLE = 'T' OR (u.ROLE = 'C' AND c.STATUS = '1'))
                  ORDER BY USER_ID";
                  $stid = oci_parse($connection, $query);
                  oci_execute($stid);

                  $sn = 1;  // Serial Number counter
                  while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    echo "<tr>\n";
                    echo "    <td>" . htmlspecialchars($sn++) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($row['FIRST_NAME']) . ' ' . htmlspecialchars($row['LAST_NAME']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($row['USERNAME']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($row['EMAIL']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($row['CONTACT_NUMBER']) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($row['ROLE']) . "</td>\n";
                    echo "    <td><a class='btn btn-danger' href='?action=delete&id=" . $row['USER_ID'] . "' onclick='return confirm(\"Are you sure you want to delete this user and all associated records?\")'>Delete</a></td>\n";
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