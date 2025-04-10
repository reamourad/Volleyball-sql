<?php
    $page_title = "Add Session";
    require_once '../database.php';

    //Collect list of teams
    $query = "SELECT TeamID, TeamName FROM Team;";
    
    $result = mysqli_query($conn, $query);
    $teams = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Get values
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $timing = mysqli_real_escape_string($conn, $_POST['dateTime']);
        $team1 = mysqli_real_escape_string($conn, $_POST['team1ID']);
        $team2 = mysqli_real_escape_string($conn, $_POST['team2ID']);
        $headCoachID = mysqli_real_escape_string($conn, $_POST['coach-id']);        

        mysqli_begin_transaction($conn);

        try {
            //Insert into Session
            $teamQuery = "
                INSERT INTO Session(Type, Address, StartDateTime, HeadCoachId, Team1ID, Team2ID) VALUES (?, ?, ?, ?, ?, ?)
            ";
            $stmt = mysqli_prepare($conn, $teamQuery);
            mysqli_stmt_bind_param($stmt, 'sssiii', $type, $address, $timing, $headCoachID, $team1, $team2);
        
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to create Session: " . mysqli_error($conn));
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

            //Get the head coach name
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
                $subject = "Game Session Confirmation";
                $first100Chars = "A new game session has been successfully created with Head Coach {$coachName}.";
            } else if ($type === "Training") {
                $subject = "Training Session Confirmation";
                $first100Chars = "A new training session has been successfully created with Head Coach {$coachName}.";
            } else {
                throw new Exception("Invalid session type.");
            }

            // Send email to the head coach
            $emailQuery = "
                INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars) VALUES (?, ?, ?, ?, ?)
            ";

            $date = date("Y-m-d H:i:s");

            $emailStmt = mysqli_prepare($conn, $emailQuery);
            mysqli_stmt_bind_param($emailStmt, 'iisss', $locationID, $headCoachID, $subject, $date, $first100Chars);

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
            <h1>Add New Session</h1>

            <!-- Confirming the addition -->
            <?php if(isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>


            <form action="create-session.php" method="POST">
                <label for="type">Session Type *:</label>
                <select name="type" id="type" required>
                    <option value="Training" <?= isset($_POST['type']) && $_POST['type'] === 'Training' ? 'selected' : '' ?>>Training</option>
                    <option value="Game" <?= isset($_POST['type']) && $_POST['type'] === 'Game' ? 'selected' : '' ?>>Game</option>
                </select>
                <label for="address">Address *:</label>
                <input type="text" name="address" id="address" required
                    value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>"
                >
                <br>
                <label for="dateTime">Select Date and Time *:</label>
                <input type="datetime-local" id="dateTime" name="dateTime" required>
                <br>
                <br>
                <label for="coach-id">Coach ID *:</label>
                <input type="text" name="coach-id" id="coach-id" 
                    value="<?= isset($_POST['coach-id']) ? htmlspecialchars($_POST['coach-id']) : '' ?>"
                >
                <br>
                <label for="team">Select Two Teams *:</label>
                <!-- First team -->
                <select name="team1ID" id="team1ID" required>
                <option value="">-- Select Team 1 --</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= $team['TeamID'] ?>">
                            (<?= htmlspecialchars($team['TeamID']) ?>) <?= htmlspecialchars($team['TeamName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <!-- Second team -->
                <select name="team2ID" id="team2ID" required>
                <option value="">-- Select Team 2 --</option>
                    <?php foreach ($teams as $team): ?>
                        <option value="<?= $team['TeamID'] ?>">
                            (<?= htmlspecialchars($team['TeamID']) ?>) <?= htmlspecialchars($team['TeamName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Add New Session</button>
            </form>
        </div>
    </main>
    
    <!-- to ensure selected teams are different -->
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const team1 = document.getElementById('team1ID').value;
            const team2 = document.getElementById('team2ID').value;

            if (team1 === team2) {
                alert("Team 1 and Team 2 must be different.");
                e.preventDefault(); // stop form from submitting
            }
        });
    </script>
</body>