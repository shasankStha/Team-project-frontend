<?php
session_start();

if (!isset($_SESSION["admin"]) || $_SESSION['loggedinUser'] === FALSE) {
  header("Location: ../login/login.php");
  exit;
}

require('../connection.php');  // Include your database connection

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
  $userId = $_GET['id'];

  $error = false;

  //Update customer status
  $updateCustomerQuery = "update CUSTOMER set status = '0' WHERE USER_ID = :userId";
  $updateCustomerStmt = oci_parse($connection, $updateCustomerQuery);
  oci_bind_by_name($updateCustomerStmt, ":userId", $userId);
  if (!oci_execute($updateCustomerStmt)) {
    $error = true;
  }


  if ($error) {
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
  <title>Admin Panel - Customers</title>
  <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

  <?php require('admindashboardheader.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">CUSTOMERS</h3>
        <?php if (!empty($message))
          echo "<p>$message</p>"; ?>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover border text-center" style="min-width: 1000px; width : 100%;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">S.N</th>
                    <th scope="col">Name</th>
                    <th scope="col">Profile Picture</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone no.</th>
                    <th scope="col">Role</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="users-data">
                  <?php
                  $query = "SELECT u.USER_ID, u.USERNAME, u.EMAIL, u.FIRST_NAME, u.LAST_NAME, u.CONTACT_NUMBER, u.ROLE ,c.profile_picture
                  FROM \"USER\" u
                  inner join customer c on c.user_id = u.user_id
                  where (u.ROLE = 'C' AND c.STATUS = '1')
                  ORDER BY USER_ID";
                  $stid = oci_parse($connection, $query);
                  oci_execute($stid);

                  $sn = 1;
                  while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                    echo "<tr>\n";
                    echo "    <td>" . htmlspecialchars($sn++) . "</td>\n";
                    echo "    <td>" . htmlspecialchars($row['FIRST_NAME']) . ' ' . htmlspecialchars($row['LAST_NAME']) . "</td>\n";
                    echo "    <td><img src='../userprofile/image/" . htmlspecialchars($row['PROFILE_PICTURE']) . "' alt='User Image' style='width:100px; height:100px;border-radius: 50%;'></td>\n";
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