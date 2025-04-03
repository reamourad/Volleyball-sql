<?php
    $page_title = "Delete Location";
    require_once "../database.php";

    //Get the location ID from the URL
    $locationID = isset($_GET['id']) ? $_GET['id'] : 0;

    //Check if the location ID is valid
    $checkQuery = "
        SELECT LocationID
        FROM Location
        WHERE LocationID = ?
    ";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "i", $locationID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $location = mysqli_fetch_assoc($result);

    if(!$location) {
        die("Location not found.");
    }

    //Delete the location from the database
    mysqli_begin_transaction($conn);

    try{
        $deleteQuery = "DELETE FROM Location WHERE LocationID = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $locationID);

        if(!mysqli_stmt_execute($stmt)){
            throw new Exception("Error deleting location: " . mysqli_error($conn));
        }

        mysqli_commit($conn);

        //Redirect to success page
        header("Location: index.php?success=Location deleted successfully");
        exit();

    } catch (Exception $e){
        mysqli_rollback($conn);
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
?>