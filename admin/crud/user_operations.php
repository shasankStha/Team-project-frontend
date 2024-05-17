<?php
require_once('../connection.php'); 

function deleteUser($userId) {
    global $connection;
    // Start transaction
    oci_execute(oci_parse($connection, 'BEGIN'));
    $error = false;

    // Delete dependent records in the CART table
    $deleteCartQuery = "DELETE FROM CART WHERE USER_ID = :userId";
    $deleteCartStmt = oci_parse($connection, $deleteCartQuery);
    oci_bind_by_name($deleteCartStmt, ":userId", $userId);
    if (!oci_execute($deleteCartStmt)) {
        $error = true;
    }

    // Delete dependent records in the CUSTOMER table
    $deleteCustomerQuery = "DELETE FROM CUSTOMER WHERE USER_ID = :userId";
    $deleteCustomerStmt = oci_parse($connection, $deleteCustomerQuery);
    oci_bind_by_name($deleteCustomerStmt, ":userId", $userId);
    if (!oci_execute($deleteCustomerStmt)) {
        $error = true;
    }

    // Proceed to delete the user if no errors
    if (!$error) {
        $deleteUserQuery = "DELETE FROM \"USER\" WHERE USER_ID = :userId";
        $deleteUserStmt = oci_parse($connection, $deleteUserQuery);
        oci_bind_by_name($deleteUserStmt, ":userId", $userId);

        if (oci_execute($deleteUserStmt)) {
            oci_execute(oci_parse($connection, 'COMMIT'));
            return "User and all associated records deleted successfully.";
        } else {
            $error = true;
        }
    }

    if ($error) {
        oci_execute(oci_parse($connection, 'ROLLBACK'));
        $e = oci_error();
        return "Error deleting user and associated records: " . $e['message'];
    }
}
?>
