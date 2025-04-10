<?php
    $page_title = "Edit Team";
    require_once '../database.php';

    //Get the team ID from the URL
    $teamID = isset($_GET['id']) ? $_GET['id'] : 0;

    //Check if the team ID is valid
    $query = "
        SELECT * 
        FROM Team 
        WHERE TeamID = ?
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $teamID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $team = mysqli_fetch_assoc($result);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Get values
        $teamName = mysqli_real_escape_string($conn, $_POST['team-name']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $locationID = mysqli_real_escape_string($conn, $_POST['location-id']);
        $captainID = mysqli_real_escape_string($conn, $_POST['captain-id']);

        mysqli_begin_transaction($conn);

        try {
            $updateQuery = "
                UPDATE Team Set
                    TeamName = ?,
                    Gender = ?,
                    LocationID = ?,
                    Captain = ?
                WHERE 
                    TeamID = ?
            ";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, 'ssssi', $teamName, $gender, $locationID, $captainID, $_GET['id']);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error executing query: " . mysqli_error($conn));
            }

            //Send email to the captain when the team is updated
            $emailQuery = "
                INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars) VALUES (?, ?, ?, ?, ?)
            ";

            // Set the email parameters
            $subject = "Team Formation Update Confirmation";
            $date = date("Y-m-d H:i:s");

            // Write the body for the email according to the changes made
            $updatedFields = [];
            $updatedFields[] = "Team {$teamName} has been updated with the following changes:";
            if ($teamName !== $team['TeamName']) {
                $updatedFields[] = "Team Name updated to '{$teamName}. '";
            }
            if ($gender !== $team['Gender']) {
                $updatedFields[] = "Gender updated to '{$gender}. '";
            }
            if ($locationID !== $team['LocationID']) {
                $updatedFields[] = "Location ID updated to {$locationID}. ";
            }
            if ($captainID !== $team['Captain']) {
                // Fetch the new captain's name
                $captainQuery = "SELECT CONCAT(FirstName, ' ', LastName) AS CaptainName FROM Person WHERE PersonID = ?";
                $captainStmt = mysqli_prepare($conn, $captainQuery);
                mysqli_stmt_bind_param($captainStmt, 'i', $captainID);
                mysqli_stmt_execute($captainStmt);
                $captainResult = mysqli_stmt_get_result($captainStmt);
                $captainRow = mysqli_fetch_assoc($captainResult);
                $captainName = $captainRow['CaptainName'];
                $updatedFields[] = "Captain updated to {$captainName}. ";
            }

            // Construct the email body
            $first100Chars = implode(", ", $updatedFields);
            if (strlen($first100Chars) > 100) {
                $first100Chars = substr($first100Chars, 0, 97) . "...";
            }

            $emailStmt = mysqli_prepare($conn, $emailQuery);
            mysqli_stmt_bind_param($emailStmt, 'iisss', $locationID, $captainID, $subject, $date, $first100Chars);

            if (!mysqli_stmt_execute($emailStmt)) {
                throw new Exception("Failed to send email: " . mysqli_error($conn));
            }

            mysqli_commit($conn);

            // Redirect with success parameter
            header("Location: index.php?success=1");
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
            <h1>Edit Team</h1>

            <!-- Confirming the addition -->
            <?php if(isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="edit.php?id=<?= $team['TeamID'] ?>" method="POST">
                <label for="team-name">Team Name *:</label>
                <input type="text" name="team-name" id="team-name" required
                    value="<?=  htmlspecialchars($team['TeamName']) ?>"
                >
                <br>
                <label for="gender">Gender (M/F) *:</label>
                <select name="gender" id="gender" required>
                    <option value="M" <?= $team['Gender'] === 'M' ? 'selected' : '' ?>>Male</option>
                    <option value="F" <?= $team['Gender'] === 'F' ? 'selected' : '' ?>>Female</option>
                </select>
                <br>
                <label for="location-id">Location ID *:</label>
                <input type="text" name="location-id" id="location-id" required
                    value="<?=  htmlspecialchars($team['LocationID']) ?>"
                >
                <br>
                <label for="captain-id">Captain ID *:</label>
                <input type="text" name="captain-id" id="captain-id" required
                    value="<?= htmlspecialchars($team['Captain']) ?>"
                >
                <br>
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Edit Team</button>
            </form>
        </div>
    </main>
</body>