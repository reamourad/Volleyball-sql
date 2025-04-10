<?php
    $page_title = "Team Details";
    require_once '../database.php';

    //Get the team ID from the URL parameter
    $teamID = isset($_GET['id']) ? $_GET['id'] : 0;

    //Get team and location name for title
    if ($teamID > 0) {
        $teamQuery = "
            SELECT 
                t.TeamName, 
                l.Name AS LocationName 
            FROM 
                Team t
            LEFT JOIN 
                Location l ON t.LocationID = l.LocationID
            WHERE 
                t.TeamID = ?
        ";
        $stmt = mysqli_prepare($conn, $teamQuery);
        mysqli_stmt_bind_param($stmt, 'i', $teamID);
        mysqli_stmt_execute($stmt);
        $teamResult = mysqli_stmt_get_result($stmt);
        
        if ($teamResult && mysqli_num_rows($teamResult) > 0) {
            $teamData = mysqli_fetch_assoc($teamResult);
            $teamName = $teamData['TeamName'];
            $locationName = $teamData['LocationName'];
        }
    }

    $query = "
            SELECT 
                r.CMN,
                p.FirstName,
                p.LastName,
                r.Position,
                TIMESTAMPDIFF(YEAR, p.DateOfBirth, CURDATE()) AS Age,
                cm.Height,
                cm.Weight,
                p.PhoneNumber,
                p.Email,
                l.Name AS LocationName
            FROM
                Role r
            LEFT JOIN
                ClubMember cm ON r.CMN = cm.CMN
            LEFT JOIN
                Person p ON cm.PersonID = p.PersonID
            LEFT JOIN
                Team t ON r.TeamID = t.TeamID
            LEFT JOIN
                Location l ON t.LocationID = l.LocationID
            WHERE
                t.TeamID = ?
            ORDER BY
                r.Position, p.LastName, p.FirstName
    ";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $teamID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $players = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (!$players) {
        die("Query failed: " . mysqli_error($conn));
    }
?>

<head>
    <title><?= $page_title ?></title>
    <link rel="stylesheet" type="text/css" href="../css/navbar.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../css/global.css">
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
        <div class="list-container">
            <h2>Team <?= htmlspecialchars($teamName ?: 'Team Not Found') ?> from <?= htmlspecialchars($locationName ?: 'Team Not Found') ?></h2>
            <button class="add-btn" onclick="window.location.href='add-player.php?id=<?= $_GET['id'] ?>'">Add Player</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>CMN</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Position</th>
                        <th>Age</th>
                        <th>Height</th>
                        <th>Weight</th>
                        <th>Phone #</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Displayed dynamically -->
                    <?php foreach ($players as $player): ?>
                        <tr>
                            <td><?= htmlspecialchars($player['CMN']) ?></td>
                            <td><?= htmlspecialchars($player['FirstName']) ?></td>
                            <td><?= htmlspecialchars($player['LastName']) ?></td>
                            <td><?= htmlspecialchars($player['Position']) ?></td>
                            <td><?= htmlspecialchars($player['Age']) ?></td>
                            <td><?= htmlspecialchars($player['Height']) ?></td>
                            <td><?= htmlspecialchars($player['Weight'])?></td>
                            <td><?= htmlspecialchars($player['PhoneNumber']) ?></td>
                            <td><?= htmlspecialchars($player['Email']) ?></td>
                            <td>
                                <a href="edit-player.php?id=<?= $teamID?>&cmn=<?=$player['CMN'] ?>" class="edit-btn">Edit</a> 
                                <a href="remove-player.php?id=<?= $teamID ?>&cmn=<?=$player['CMN']?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this player?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>