<?php
    $page_title = "Add Team";
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
            <li><a href="index.php">Team Formation</a></li>
            <li><a href="#">Events</a></li>

            <!-- Email Logs Dropdown -->
            <li class="dropdown">
                <a href="#">Email Logs</a>
                <ul class="dropdown-content">
                    <li><a href="#">Subcategory 1</a></li>
                    <li><a href="#">Subcategory 2</a></li>
                    <li><a href="#">Subcategory 3</a></li>
                </ul>
            </li>

            <!-- Reports Dropdown -->
            <li class="dropdown">
                <a href="#">Reports</a>
                <ul class="dropdown-content">
                    <li><a href="#">Subcategory 1</a></li>
                    <li><a href="#">Subcategory 2</a></li>
                    <li><a href="#">Subcategory 3</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Main Section -->
    <main>
        <div class="form-container">
            <h1>Add New Team</h1>

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
                <button type="submit">Add Team</button>
            </form>
        </div>
    </main>
</body>