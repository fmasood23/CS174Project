<?php
    include 'mysql_connector.php';
    include 'mysql_table_creation.php';

    global $conn;
    createUsersTable($conn);
    $conn->close();
?>