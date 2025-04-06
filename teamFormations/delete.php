<?php
    $page_title = "Delete Team Formation";
    require_once "../database.php";

    // Get the team formation ID from the URL
    $teamID = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Get team info
    $checkQuery = "
        SELECT 
            TeamID, 
            Captain AS CaptainID, 
            TeamName, 
            LocationID 
        FROM Team 
        WHERE TeamID = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "i", $teamID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $team = mysqli_fetch_assoc($result);

    if (!$team) {
        header("Location: index.php?error=" . urlencode("Team formation not found."));
        exit;
    }

    $captainID = $team['CaptainID'];
    $teamName = $team['TeamName'];
    $locationID = $team['LocationID'];

    // Get captain name
    $captainQuery = "SELECT CONCAT(FirstName, ' ', LastName) AS CaptainName FROM Person WHERE PersonID = ?";
    $captainStmt = mysqli_prepare($conn, $captainQuery);
    mysqli_stmt_bind_param($captainStmt, 'i', $captainID);
    mysqli_stmt_execute($captainStmt);
    $captainResult = mysqli_stmt_get_result($captainStmt);
    $captainRow = mysqli_fetch_assoc($captainResult);

    $captainName = $captainRow ? $captainRow['CaptainName'] : "Captain";

    // Store email in Email table
    $subject = "Team Formation Deletion Confirmation";
    $date = date("Y-m-d H:i:s");
    $first100Chars = "Team {$teamName} has been successfully deleted. Captain {$captainName} has been notified.";

    $emailQuery = "
        INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars)
        VALUES (?, ?, ?, ?, ?)
    ";
    $emailStmt = mysqli_prepare($conn, $emailQuery);
    mysqli_stmt_bind_param($emailStmt, 'iisss', $locationID, $captainID, $subject, $date, $first100Chars);

    if (!mysqli_stmt_execute($emailStmt)) {
        // Log error but still allow redirect
        error_log("Failed to insert email: " . mysqli_stmt_error($emailStmt));
    }

    // Delete the team
    $deleteQuery = "DELETE FROM Team WHERE TeamID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "i", $teamID);

    if (!mysqli_stmt_execute($deleteStmt)) {
        header("Location: index.php?error=" . urlencode("Error deleting team formation: " . mysqli_stmt_error($deleteStmt)));
        exit;
    }
    header("Location: index.php?success=" . urlencode("Team formation deleted successfully."));
    exit;
?>