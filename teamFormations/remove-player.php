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

    //Send email to the player
    $emailQuery = "
        INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars) VALUES (?, ?, ?, ?, ?)
    ";

    //Set the email parameters
    $subject = "Removal from Team";
    $date = date("Y-m-d H:i:s");

    $teamQuery = "SELECT TeamName, LocationID FROM Team WHERE TeamID = ?";
    $teamStmt = mysqli_prepare($conn, $teamQuery);
    mysqli_stmt_bind_param($teamStmt, 'i', $teamID);
    mysqli_stmt_execute($teamStmt);
    $teamResult = mysqli_stmt_get_result($teamStmt);
    $teamRow = mysqli_fetch_assoc($teamResult);
    $teamName = $teamRow['TeamName'];
    $locationID = $teamRow['LocationID'];

    $first100Chars = "You have been removed from the {$teamName} Team. Good luck in your future endeavors.";

    $emailStmt = mysqli_prepare($conn, $emailQuery);
    mysqli_stmt_bind_param($emailStmt, 'iisss', $locationID, $cmn, $subject, $date, $first100Chars);

    if(!mysqli_stmt_execute($emailStmt)) {
        throw new Exception("Failed to send email: " . mysqli_error($conn));
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