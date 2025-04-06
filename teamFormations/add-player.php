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