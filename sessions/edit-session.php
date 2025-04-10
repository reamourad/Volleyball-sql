<?php
    $page_title = "Edit Session";
    require_once '../database.php';

    // Collect list of teams
    $query = "SELECT TeamID, TeamName FROM Team;";
    $result = mysqli_query($conn, $query);
    $teams = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Get session details
    $sessionID = $_GET['id'] ?? null;
    if (!$sessionID) {
        die("Session ID is required.");
    }

    $sessionQuery = "SELECT * FROM Session WHERE SessionID = ?";
    $stmt = mysqli_prepare($conn, $sessionQuery);
    mysqli_stmt_bind_param($stmt, 'i', $sessionID);
    mysqli_stmt_execute($stmt);
    $sessionResult = mysqli_stmt_get_result($stmt);
    $session = mysqli_fetch_assoc($sessionResult);

    if (!$session) {
        die("Session not found.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get values
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $timing = mysqli_real_escape_string($conn, $_POST['dateTime']);
        $team1 = mysqli_real_escape_string($conn, $_POST['team1ID']);
        $team2 = mysqli_real_escape_string($conn, $_POST['team2ID']);
        $headCoachID = mysqli_real_escape_string($conn, $_POST['coach-id']);

        mysqli_begin_transaction($conn);

        try {
            // Update Session
            $updateQuery = "
                UPDATE Session SET 
                    Type = ?, Address = ?, 
                    StartDateTime = ?, 
                    HeadCoachId = ?, 
                    Team1ID = ?, 
                    Team2ID = ?
                WHERE SessionID = ?
            ";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, 'sssiiii', $type, $address, $timing, $headCoachID, $team1, $team2, $sessionID);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to update Session: " . mysqli_error($conn));
            }

            // Get the location from where the head coach is last registered
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
            $locationID = $locationRow['LocationID'];

            if (!$locationRow) {
                throw new Exception("Failed to retrieve location for the head coach.");
            }

            // Get the head coach name
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

            // Determine if it's a game or training and set the subject and email body accordingly
            if ($type === "Game") {
                $subject = "Game Session Update";
                $first100Chars = "The game session has been successfully updated with Head Coach {$coachName}.";
            } else if ($type === "Training") {
                $subject = "Training Session Update";
                $first100Chars = "The training session has been successfully updated with Head Coach {$coachName}.";
            } else {
                throw new Exception("Invalid session type.");
            }

            // Log the email update for the head coach
            $emailQuery = "
                INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars) VALUES (?, ?, ?, ?, ?)
            ";

            $date = date("Y-m-d H:i:s");

            $emailStmt = mysqli_prepare($conn, $emailQuery);
            mysqli_stmt_bind_param($emailStmt, 'iisss', $locationID, $headCoachID, $subject, $date, $first100Chars);

            if (!mysqli_stmt_execute($emailStmt)) {
                throw new Exception("Failed to log email update: " . mysqli_error($conn));
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
            <li><a href="../emailLog/index.php">Email Logs</a></li>
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
            <h1>Edit Session</h1>

            <?php if (isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="edit-session.php?id=<?= $sessionID ?>" method="POST">
                <label for="type">Session Type *:</label>
                <select name="type" id="type" required>
                    <option value="Training" <?= $session['Type'] === 'Training' ? 'selected' : '' ?>>Training</option>
                    <option value="Game" <?= $session['Type'] === 'Game' ? 'selected' : '' ?>>Game</option>
                </select>
                <label for="address">Address *:</label>
                <input type="text" name="address" id="address" required
                    value="<?= htmlspecialchars($session['Address']) ?>"
                >
                <br>
                <label for="dateTime">Select Date and Time *:</label>
                <input type="datetime-local" id="dateTime" name="dateTime" required
                    value="<?= date('Y-m-d\TH:i', strtotime($session['StartDateTime'])) ?>"
                >
                <br>
                <br>
                <label for="coach-id">Coach ID *:</label>
                <input type="text" name="coach-id" id="coach-id" 
                    value="<?= htmlspecialchars($session['HeadCoachID']) ?>"
                >
                <br>
                <label for="team">Select Two Teams *:</label>
                <select name="team1ID" id="team1ID" required>
                    <option value="">-- Select Team 1 --</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= $team['TeamID'] ?>" <?= $session['Team1ID'] == $team['TeamID'] ? 'selected' : '' ?>>
                            (<?= htmlspecialchars($team['TeamID']) ?>) <?= htmlspecialchars($team['TeamName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="team2ID" id="team2ID" required>
                    <option value="">-- Select Team 2 --</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= $team['TeamID'] ?>" <?= $session['Team2ID'] == $team['TeamID'] ? 'selected' : '' ?>>
                            (<?= htmlspecialchars($team['TeamID']) ?>) <?= htmlspecialchars($team['TeamName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Update Session</button>
            </form>
        </div>
    </main>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const team1 = document.getElementById('team1ID').value;
            const team2 = document.getElementById('team2ID').value;

            if (team1 === team2) {
                alert("Team 1 and Team 2 must be different.");
                e.preventDefault();
            }
        });
    </script>
</body>