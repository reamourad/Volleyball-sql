<?php
    $page_title = "Delete Session";
    require_once "../database.php";

    // Get the session ID from the URL
    $sessionID = isset($_GET['id']) ? $_GET['id'] : 0;

    //Check if the sessionID are valid
    $checkQuery = "
        SELECT * 
        FROM Session 
        WHERE SessionID = ?
    ";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "i", $sessionID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $session = mysqli_fetch_assoc($result);

    if (!$session) {
        header("Location: index.php?id=$sessionID&error=Session not found.");
        exit;
    }

    //Delete the session
    try{
        $deleteQuery = "DELETE FROM Session WHERE SessionID = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $sessionID);
        
        if(!mysqli_stmt_execute($stmt)) {
            die("Error deleting session: " . mysqli_error($conn));
        } 

        mysqli_commit($conn);

        //Redirect to success page
        header("Location: index.php?id=$sessionID&success=Session canceled successfully.");
        exit();

    } catch (Exception $e) {
        header("Location: index.php?id=$sessionID&error=Failed to cancel session.");        
        exit;
    }
?>