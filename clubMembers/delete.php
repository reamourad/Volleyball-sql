<?php
    $page_title = "Delete Club Member";
    require_once '../database.php';

    //Get the ID of selected family member
    $personID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    //Check if club member exist
    $checkQuery = "
        SELECT 
            p.PersonID FROM Person p
        JOIN 
            ClubMember cm ON p.PersonID = cm.PersonID
        WHERE 
            p.PersonID = ?
    ";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "i", $personID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $clubMember = mysqli_fetch_assoc($result);

    if (!$clubMember) {
        die("Family member not found");
    }

    //Delete the tuple
    mysqli_begin_transaction($conn);

    try{
        //Delete from FamilyMember
        $deleteClubMemberQuery = "DELETE FROM ClubMember WHERE PersonID = ?";
        $stmt = mysqli_prepare($conn, $deleteClubMemberQuery);
        mysqli_stmt_bind_param($stmt, "i", $personID);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to delete from ClubMember table");
        }

        //Delete from Person
        $deletePersonQuery = "DELETE FROM Person WHERE PersonID = ?";
        $stmt = mysqli_prepare($conn, $deletePersonQuery);
        mysqli_stmt_bind_param($stmt, "i", $personID);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to delete from Person table");
        }

        mysqli_commit($conn);

        //Redirect to success page
        header("Location: index.php?success=Club member deleted successfully");
        exit();
        
    } catch (Exception $e){
        mysqli_rollback($conn);
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
?>