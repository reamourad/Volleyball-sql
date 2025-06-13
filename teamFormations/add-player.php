<?php
    $page_title = "Add Player";
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
        $CMN = mysqli_real_escape_string($conn, $_POST['CMN']);
        $Position = mysqli_real_escape_string($conn, $_POST['position']);

        mysqli_begin_transaction($conn);

        try {
            $addQuery = "
                INSERT INTO Role (CMN, TeamID, Position) 
                VALUES (?, ?, ?);
            ";
            $stmt = mysqli_prepare($conn, $addQuery);
            mysqli_stmt_bind_param($stmt, 'sis', $CMN, $_GET['id'], $Position);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error executing query: " . mysqli_error($conn));
            }

            //Send email to the player for confirmation
            $emailQuery = "
                INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars) VALUES (?, ?, ?, ?, ?)
            ";

            //Set the email parameters
            $subject = "Welcome to the Team!";
            $date = date("Y-m-d H:i:s");

            //Write the body for the email
            $teamQuery = "SELECT TeamName, LocationID FROM Team WHERE TeamID = ?";
            $teamStmt = mysqli_prepare($conn, $teamQuery);
            mysqli_stmt_bind_param($teamStmt, 'i', $teamID);
            mysqli_stmt_execute($teamStmt);
            $teamResult = mysqli_stmt_get_result($teamStmt);
            $teamRow = mysqli_fetch_assoc($teamResult);
            $teamName = $teamRow['TeamName'];
            $locationID = $teamRow['LocationID'];

            $first100Chars = "Welcome to the team! Go {$teamName}! You have been assigned the position of {$Position}.";

            $emailStmt = mysqli_prepare($conn, $emailQuery);
            mysqli_stmt_bind_param($emailStmt, 'iisss', $locationID, $CMN, $subject, $date, $first100Chars);

            if(!mysqli_stmt_execute($emailStmt)) {
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
            <h1>Add Player</h1>

            <!-- Confirming the addition -->
            <?php if(isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="add-player.php?id=<?= $teamID ?>" method="POST">
                <label for="CMN">Club Membership Number *:</label>
                <input type="text" name="CMN" id="CMN" required
                    value="<?= isset($_POST['CMN']) ? htmlspecialchars($_POST['CMN']) : '' ?>"
                >
                <br>
                <label for="position">Player Position *:</label>
                    <select name="position" id="position" required>
                        <option value="Outside Hitter">Outside Hitter</option>
                        <option value="Opposite">Opposite</option>
                        <option value="Setter">Setter</option>
                        <option value="Middle Blocker">Middle Blocker</option>
                        <option value="Libero">Libero</option>
                        <option value="Defensive Specialist">Defensive Specialist</option>
                        <option value="Serving Specialist">Serving Specialist</option>
                    </select>
                <br>
                <p>* This indicates that the field must be filled</p>
                <button type="submit"> Add Player</button>
            </form>
        </div>
    </main>
</body>