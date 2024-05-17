<?php
require('../connection.php');  // Include your database connection

function deleteContactQuery($delete_id) {
    global $connection;
    $deleteQuery = "DELETE FROM CONTACT_US WHERE CONTACTUS_ID = :delete_id";
    $deleteStmt = oci_parse($connection, $deleteQuery);
    oci_bind_by_name($deleteStmt, ":delete_id", $delete_id);

    // Execute the delete query
    if (oci_execute($deleteStmt)) {
        // Redirect to refresh the page and avoid resubmission issues
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $e = oci_error($deleteStmt);
        return "Error deleting query: " . $e['message'];
    }
}
?>