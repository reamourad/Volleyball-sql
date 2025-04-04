<?php
    $page_title = "Delete Team Formation";
    require_once "../database.php";

    // Get the team formation ID from the URL
    $teamID = isset($_GET['id']) ? $_GET['id'] : 0;

    //Check if the teamID is valid
    $checkQuery = "
        SELECT TeamID 
        FROM Team 
        WHERE TeamID = ?
    ";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "i", $teamID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $team = mysqli_fetch_assoc($result);

    if(!$team) {
        header("Location: index.php?error=Team formation not found.");
        exit;
    }

    //Delete the team formation
    try{
        $deleteQuery = "DELETE FROM Team WHERE TeamID = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $teamID);
        
        if(!mysqli_stmt_execute($stmt)) {
            die("Error deleting team formation: " . mysqli_error($conn));
        } 

        mysqli_commit($conn);

        //Redirect to success page
        header("Location: index.php?success=Team formation deleted successfully.");
        exit();

    } catch (Exception $e) {
        header("Location: index.php?error=Failed to delete team formation.");
        exit;
    }
?>