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

    //Save info for email
    $headCoachID = $session['HeadCoachID'];
    $type = $session['Type'];

    try {
        // Get the location where the head coach last worked
        $locationQuery = "
            SELECT LocationID 
            FROM Contract 
            WHERE EmployeeID = ? 
            AND Role = 'Coach' 
            ORDER BY StartDate DESC 
            LIMIT 1
        ";
        $locationStmt = mysqli_prepare($conn, $locationQuery);
        mysqli_stmt_bind_param($locationStmt, 'i', $headCoachID);
        mysqli_stmt_execute($locationStmt);
        $locationResult = mysqli_stmt_get_result($locationStmt);
        $locationRow = mysqli_fetch_assoc($locationResult);

        if (!$locationRow) {
            throw new Exception("Failed to retrieve location for the head coach.");
        }

        $locationID = $locationRow['LocationID'];

        //Get the coach name
        $coachQuery = "
            SELECT CONCAT(FirstName, ' ', LastName) AS CoachName 
            FROM Person 
            WHERE PersonID = ?
        ";
        $coachStmt = mysqli_prepare($conn, $coachQuery);
        mysqli_stmt_bind_param($coachStmt, 'i', $headCoachID);
        mysqli_stmt_execute($coachStmt);
        $coachResult = mysqli_stmt_get_result($coachStmt);
        $coachRow = mysqli_fetch_assoc($coachResult);
        $coachName = $coachRow['CoachName'];

        // Determine subject and email message
        if ($type === "Game") {
            $subject = "Game Session Cancellation";
            $first100Chars = "The game session with Head Coach {$coachName} has been successfully canceled.";
        } else if ($type === "Training") {
            $subject = "Training Session Cancellation";
            $first100Chars = "The training session with Head Coach {$coachName} has been successfully canceled.";
        } else {
            throw new Exception("Invalid session type.");
        }

        //Send the email
        $emailQuery = "
            INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars) 
            VALUES (?, ?, ?, ?, ?)
        ";
        $date = date("Y-m-d H:i:s");
        $emailStmt = mysqli_prepare($conn, $emailQuery);
        mysqli_stmt_bind_param($emailStmt, 'iisss', $locationID, $headCoachID, $subject, $date, $first100Chars);

        if (!mysqli_stmt_execute($emailStmt)) {
            throw new Exception("Failed to send email: " . mysqli_error($conn));
        }

        // Delete the session
        $deleteQuery = "DELETE FROM Session WHERE SessionID = ?";
        $stmt = mysqli_prepare($conn, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $sessionID);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error deleting session: " . mysqli_error($conn));
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