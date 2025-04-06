<?php
    $page_title = "Delete Player";
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

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Get values
        $CMN = mysqli_real_escape_string($conn, $_POST['cmn']);
        $Position = mysqli_real_escape_string($conn, $_POST['Position']);

        mysqli_begin_transaction($conn);

        try {
            $updateQuery = "
                UPDATE Role Set
                    CMN = ?,
                    Position = ?
                WHERE 
                    TeamID = ? AND CMN = ?
            ";
            $stmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmt, 'isii', $CMN, $Position, $_GET['id'], $_GET['cmn']);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error executing query: " . mysqli_error($conn));
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
            <h1>Edit Team</h1>

            <!-- Confirming the update -->
            <?php if(isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <<form action="edit-player.php?id=<?= $teamID ?>&cmn=<?= $cmn ?>" method="POST">
                <label for="cmn">Club Membership Number *:</label>
                <input type="text" name="cmn" id="cmn" required
                    value="<?=  htmlspecialchars($role['CMN']) ?>"
                >
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