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
    mysqli_stmt_bind_param($stmt, "i", $$sessionID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $session = mysqli_fetch_assoc($result);

    if (!$session) {
        header("Location: index.php?id=$sessionID&error=Session not found.");
        exit;
    }

    /* TODO
    //Send email to the player
    $emailQuery = "
        INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars) VALUES (?, ?, ?, ?, ?)
    ";

    //Set the email parameters
    $subject = "Removal from Team";
    $date = date("Y-m-d H:i:s");

    $sessionQuery = "SELECT TeamName, LocationID FROM Team WHERE TeamID = ?";
    $sessionStmt = mysqli_prepare($conn, $teamQuery);
    mysqli_stmt_bind_param($teamStmt, 'i', $teamID);
    mysqli_stmt_execute($teamStmt);
    $sessionResult = mysqli_stmt_get_result($teamStmt);
    $sessionRow = mysqli_fetch_assoc($teamResult);
    $sessionName = $sessionRow['TeamName'];
    $locationID = $sessionRow['LocationID'];

    $first100Chars = "You have been removed from the {$teamName} Team. Good luck in your future endeavors.";

    $emailStmt = mysqli_prepare($conn, $emailQuery);
    mysqli_stmt_bind_param($emailStmt, 'iisss', $locationID, $cmn, $subject, $date, $first100Chars);

    if(!mysqli_stmt_execute($emailStmt)) {
        throw new Exception("Failed to send email: " . mysqli_error($conn));
    } */

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