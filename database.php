<?php
    //Connect to the database
    $servername = "brc353.encs.concordia.ca";
    $username = "brc353_4";
    $password = "7nwBig+k";
    $dbname = "brc353_4";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password);

    // Check connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
?>