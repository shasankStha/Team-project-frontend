<?php
session_start();

if (!isset($_SESSION["admin"]) || $_SESSION['loggedinUser'] === FALSE) {
    header("Location: ../login/login.php");
    exit;
}

require ('../connection.php');  // Include your database connection

require_once('crud/userqueries_operations.php');  // Include the operations file

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $message = deleteContactQuery($delete_id);
    if (!empty($message)) {
        echo "<script>alert('$message');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Queries</title>
    <?php require ('inc/links.php'); ?>
</head>

<body class="bg-light">
    <?php require ('admindashboardheader.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">USER QUERIES</h3>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover border text-center" style="min-width: 1000px; width : 100%;">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">S.N</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Message</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require ('../connection.php');
                                    $query = "SELECT * FROM CONTACT_US ORDER BY CONTACTUS_ID";
                                    $stid = oci_parse($connection, $query);
                                    oci_execute($stid);

                                    while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['CONTACTUS_ID']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['NAME']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['EMAIL']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['SUBJECT']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['MESSAGE']) . "</td>";
                                        echo "<td>";
                                        echo "<form method='GET' action='' onsubmit='return confirmDelete();'>";
                                        echo "<input type='hidden' name='delete_id' value='" . $row['CONTACTUS_ID'] . "'>";
                                        echo "<button type='submit' class='btn btn-danger'>Delete</button>";
                                        echo "</form>";
                                        echo "</td>";
                                        echo "</tr>";
                                        echo "</tr>";
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
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this query? This action cannot be undone.');
        }
    </script>
</body>

</html>