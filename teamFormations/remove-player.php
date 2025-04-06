<?php
    $page_title = "Delete Player";
    require_once "../database.php";

    // Get the team formation ID and CMN from the URL
    $teamID = isset($_GET['id']) ? $_GET['id'] : 0;
    $cmn=isset($_GET['cmn']) ? $_GET['cmn'] : 0;

    //Check if the teamID and CMN are valid
    $checkQuery = "
        SELECT * 
        FROM Role 
        WHERE TeamID = ? AND CMN = ?
    ";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "ii", $teamID, $cmn);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $team = mysqli_fetch_assoc($result);

    if (!$team) {
        header("Location: show-details.php?id=$teamID&error=Player not found in current team.");
        exit;
    }

    //Delete the player from the team
    try{
        $deleteQuery = "DELETE FROM Role WHERE TeamID = ? AND CMN = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "ii", $teamID, $cmn);
        
        if(!mysqli_stmt_execute($stmt)) {
            die("Error deleting player: " . mysqli_error($conn));
        } 

        mysqli_commit($conn);

        //Redirect to success page
        header("Location: show-details.php?id=$teamID&success=Player deleted successfully.");
        exit();

    } catch (Exception $e) {
        header("Location: show-details.php?id=$teamID&error=Failed to delete player.");        
        exit;
    }
?>