<?php
    $page_title = "Delete Family Member";
    require_once '../database.php';

    //Get the ID of selected family member
    $personID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    //Check if the family member exists
    $checkQuery = "
        SELECT 
            p.PersonID FROM Person p
        JOIN 
            FamilyMember fm ON p.PersonID = fm.PersonID
        WHERE 
            p.PersonID = ?
    ";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "i", $personID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $familyMember = mysqli_fetch_assoc($result);

    if (!$familyMember) {
        die("Family member not found");
    }

    //Check if the family member is an alternative family member
    $checkAlternativeQuery = "
        Select 
            COUNT(*) as count 
        FROM 
            FamilyMember
        Where 
            AlternativeFamilyID = ?
    ";
    $stmt = mysqli_prepare($conn, $checkAlternativeQuery);
    mysqli_stmt_bind_param($stmt, "i", $personID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $count = mysqli_fetch_assoc($result)['count'];

    if ($count > 0) {
        header("Location: index.php?error=This family member is referenced as an alternative family member and cannot be deleted");
        exit();
    }

    //Delete the tuple
    mysqli_begin_transaction($conn);

    try{
        //Delete from FamilyMember
        $deleteFamilyQuery = "DELETE FROM FamilyMember WHERE PersonID = ?";
        $stmt = mysqli_prepare($conn, $deleteFamilyQuery);
        mysqli_stmt_bind_param($stmt, "i", $personID);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to delete from FamilyMember table");
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
        header("Location: index.php?success=Family member deleted successfully");
        exit();
        
    } catch (Exception $e){
        mysqli_rollback($conn);
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
?>