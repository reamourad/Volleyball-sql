<?php
    $page_title = "Add Team";
    require_once '../database.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Get values
        $teamName = mysqli_real_escape_string($conn, $_POST['team-name']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $locationID = mysqli_real_escape_string($conn, $_POST['location-id']);
        $captainID = mysqli_real_escape_string($conn, $_POST['captain-id']);

        mysqli_begin_transaction($conn);

        try {
            //Insert into Person
            $teamQuery = "
                INSERT INTO Team (TeamName, Gender, LocationID, Captain) VALUES (?, ?, ?, ?)
            ";
            $stmt = mysqli_prepare($conn, $teamQuery);
            mysqli_stmt_bind_param($stmt, 'ssii', $teamName, $gender, $locationID, $captainID);
        
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to add Team: " . mysqli_error($conn));
            }

            //Send email to the captain
            $emailQuery = "
                INSERT INTO Email (locationID, recipientID, Subject, Date, First100Chars) VALUES (?, ?, ?, ?, ?)
            ";

            //Set the email parameters
            $subject = "Team Formation Confirmation";
            $date = date("Y-m-d H:i:s");

            //Write the body for the email
            $captainQuery = "SELECT CONCAT(FirstName, ' ', LastName) AS CaptainName FROM Person WHERE PersonID = ?";
            $captainStmt = mysqli_prepare($conn, $captainQuery);
            mysqli_stmt_bind_param($captainStmt, 'i', $captainID);
            mysqli_stmt_execute($captainStmt);
            $captainResult = mysqli_stmt_get_result($captainStmt);
            $captainRow = mysqli_fetch_assoc($captainResult);
            $captainName = $captainRow['CaptainName'];

            $first100Chars = "Team {$teamName} has been successfully formed with Captain {$captainName}.";

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
            <li><a href="index.php">Team Formation</a></li>
            <li><a href="../emailLog/index.php">Email Logs</a></li>

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

            <form action="add.php" method="POST">
                <label for="team-name">Team Name *:</label>
                <input type="text" name="team-name" id="team-name" required
                    value="<?= isset($_POST['team-name']) ? htmlspecialchars($_POST['team-name']) : '' ?>"
                >
                <br>
                <label for="gender">Gender (M/F) *:</label>
                <select name="gender" id="gender" required>
                    <option value="M" <?= isset($_POST['gender']) && $_POST['gender'] === 'M' ? 'selected' : '' ?>>Male</option>
                    <option value="F" <?= isset($_POST['gender']) && $_POST['gender'] === 'F' ? 'selected' : '' ?>>Female</option>
                </select>
                <br>
                <label for="location-id">Location ID *:</label>
                <input type="text" name="location-id" id="location-id" required
                    value="<?= isset($_POST['location-id']) ? htmlspecialchars($_POST['location-id']) : '' ?>"
                >
                <br>
                <label for="captain-id">Captain ID *:</label>
                <input type="text" name="captain-id" id="captain-id" required
                    value="<?= isset($_POST['captain-id']) ? htmlspecialchars($_POST['captain-id']) : '' ?>"
                >
                <br>
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Add Team</button>
            </form>
        </div>
    </main>
</body>