<?php
    $page_title = "Edit Player";
    require_once "../database.php";

    // Get the team formation ID and CMN from the URL
    $teamID = isset($_GET['id']) ? $_GET['id'] : 0;
    $cmn=isset($_GET['cmn']) ? $_GET['cmn'] : 0;

    //Check if the teamID and CMN is valid
    $checkQuery = "
        SELECT * 
        FROM Role 
        WHERE TeamID = ? AND CMN = ?
    ";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "ii", $teamID, $cmn);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $role = mysqli_fetch_assoc($result);

    //Extract player info
    $query = "
    SELECT 
        p.FirstName,
        p.LastName
    FROM
        ClubMember cm
    JOIN
        Person p ON cm.PersonID = p.PersonID
    WHERE cm.CMN = ?
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $cmn);
    mysqli_stmt_execute($stmt);
    $result2 = mysqli_stmt_get_result($stmt);
    $player = mysqli_fetch_assoc($result2);


    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Get values
        $Position = mysqli_real_escape_string($conn, $_POST['Position']);

        mysqli_begin_transaction($conn);

        try {
            $updateQuery = "
                UPDATE Role Set
                    Position = ?
                WHERE 
                    TeamID = ? AND CMN = ?
            ";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, 'sii', $Position, $_GET['id'], $_GET['cmn']);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error executing query: " . mysqli_error($conn));
            }

            // Send email to the player for confirmation
            $emailQuery = "
                INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars) VALUES (?, ?, ?, ?, ?)
            ";

            // Set the email parameters
            $subject = "New Position Assigned!";
            $date = date("Y-m-d H:i:s");

            // Write the body for the email
            $teamQuery = "SELECT TeamName, LocationID FROM Team WHERE TeamID = ?";
            $teamStmt = mysqli_prepare($conn, $teamQuery);
            mysqli_stmt_bind_param($teamStmt, 'i', $teamID);
            mysqli_stmt_execute($teamStmt);
            $teamResult = mysqli_stmt_get_result($teamStmt);
            $teamRow = mysqli_fetch_assoc($teamResult);
            $teamName = $teamRow['TeamName'];
            $locationID = $teamRow['LocationID'];

            $first100Chars = "Congratulations! You have been assigned the position of {$Position} in the {$teamName} team.";

            $emailStmt = mysqli_prepare($conn, $emailQuery);
            mysqli_stmt_bind_param($emailStmt, 'iisss', $locationID, $cmn, $subject, $date, $first100Chars);

            if (!mysqli_stmt_execute($emailStmt)) {
                throw new Exception("Failed to send email: " . mysqli_error($conn));
            }

            mysqli_commit($conn);

            // Redirect with success parameter
            header("Location: show-details.php?id=$teamID&success=1");

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
            <li><a href="../sessions/index.php">Sessions</a></li>
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
            <h1>Edit Player Position</h1>

            <!-- Confirming the update -->
            <?php if(isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="edit-player.php?id=<?= $teamID ?>&cmn=<?= $cmn ?>" method="POST">
                <label>Player: <?=  htmlspecialchars($player['FirstName']) ?> <?=  htmlspecialchars($player['LastName']) ?></label>
                <label>Club Membership Number: <?=  htmlspecialchars($role['CMN']) ?></label>
                <br>
                <label for="Position">Player Position *:</label>
                <select name="Position" id="Position" required>
                    <option value="Outside Hitter" <?= $role['Position'] === 'Outside Hitter' ? 'selected' : '' ?>>Outside Hitter</option>
                    <option value="Opposite" <?= $role['Position'] === 'Opposite' ? 'selected' : '' ?>>Opposite</option>
                    <option value="Setter" <?= $role['Position'] === 'Setter' ? 'selected' : '' ?>>Setter</option>
                    <option value="Middle Blocker" <?= $role['Position'] === 'Middle Blocker' ? 'selected' : '' ?>>Middle Blocker</option>
                    <option value="Libero" <?= $role['Position'] === 'Libero' ? 'selected' : '' ?>>Libero</option>
                    <option value="Defensive Specialist" <?= $role['Position'] === 'Defensive Specialist' ? 'selected' : '' ?>>Defensive Specialist</option>
                    <option value="Serving Specialist" <?= $role['Position'] === 'Serving Specialist' ? 'selected' : '' ?>>Serving Specialist</option>
                </select>
                <br>
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Edit Player</button>
            </form>
        </div>
    </main>
</body>