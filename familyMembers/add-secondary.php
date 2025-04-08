<?php
    $page_title = "Link to Secondary";
    require_once '../database.php';

    // Get the family member ID from the URL
    $personID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get values
        $secondaryID = mysqli_real_escape_string($conn, $_POST['secondaryID']);
        
        try {
            // Begin transaction
            mysqli_begin_transaction($conn);

            // Update AlternativeFamilyID in FamilyMember table
            $updateFamilyMemberQuery = "
                UPDATE FamilyMember
                SET AlternativeFamilyID = ?
                WHERE PersonID = ?
            ";
            $updateStmt = mysqli_prepare($conn, $updateFamilyMemberQuery);
            mysqli_stmt_bind_param($updateStmt, 'ii', $secondaryID, $personID);

            if (!mysqli_stmt_execute($updateStmt)) {
                throw new Exception("Failed to update FamilyMember entry: " . mysqli_error($conn));
            }

            // Commit transaction
            mysqli_commit($conn);

            // Redirect with success parameter
            header("Location: show-details.php?id=" . $personID . "&success=1");
            exit;
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = $e->getMessage();
        }
    }
?>

<head>
    <title><?= $page_title ?></title>
    <link rel="stylesheet" type="text/css" href="../css/navbar.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../css/forms.css">
</head>
<body>
    <!-- Navbar Section -->
    <nav>
        <h2>MYVC Management System</h2>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../clubMembers/index.php">Club Members</a></li>
            <li><a href="../familyMembers/index.php">Family Members</a></li>
            <li><a href="../personnels/index.php">Personnel</a></li>
            <li><a href="../locations/index.php">Locations</a></li>
            <li><a href="../teamFormations/index.php">Team Formation</a></li>
            <li><a href="../emailLog/index.php">Email Logs</a></li>

            <!-- Reports Dropdown -->
            <li class="dropdown">
                <a href="#">Queries</a>
                <ul class="dropdown-content">
                    <li><a href="../queries/query9.php">Query 9</a></li>
                    <li><a href="../queries/query10.php">Query 10</a></li>
                    <li><a href="../queries/query11.php">Query 11</a></li>
                    <li><a href="../queries/query12.php">Query 12</a></li>
                    <li><a href="../queries/query13.php">Query 13</a></li>
                    <li><a href="../queries/query14.php">Query 14</a></li>
                    <li><a href="../queries/query15.php">Query 15</a></li>
                    <li><a href="../queries/query16.php">Query 16</a></li>
                    <li><a href="../queries/query17.php">Query 17</a></li>
                    <li><a href="../queries/query18.php">Query 18</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Main Section -->
    <main>
        <div class="form-container">
            <h1>Link to Secondary Family</h1>
            
            <!-- Display error if any -->
            <?php if (isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="add-secondary.php?id=<?= $personID ?>" method="POST">
                <label for="secondaryID">Secondary Family Member ID *:</label>
                <input type="text" name="secondaryID" id="secondaryID" required
                    value="<?= isset($_POST['secondaryID']) ? htmlspecialchars($_POST['secondaryID']) : '' ?>"
                >
                <br>

                <p>* This indicates that the field must be filled</p>
                <button type="submit">Link</button>
            </form>
        </div>
    </main>
</body>
</html>