<?php
    $page_title = "Query 11";
    require_once '../database.php';

    
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

    // Validate the dates
    if ($start_date && $end_date && strtotime($start_date) <= strtotime($end_date)) {
       
        $query = "
            SELECT 
                L.Name AS LocationName,
                COUNT(CASE WHEN S.Type = 'Training' THEN S.SessionID END) AS TotalTrainingSessions,
                SUM(CASE WHEN S.Type = 'Training' THEN (SELECT COUNT(*) 
                                                        FROM Role R 
                                                        WHERE R.TeamID = S.Team1ID OR R.TeamID = S.Team2ID) END) AS TotalPlayersInTraining,
                COUNT(CASE WHEN S.Type = 'Game' THEN S.SessionID END) AS TotalGameSessions,
                SUM(CASE WHEN S.Type = 'Game' THEN (SELECT COUNT(*) 
                                                    FROM Role R 
                                                    WHERE R.TeamID = S.Team1ID OR R.TeamID = S.Team2ID) END) AS TotalPlayersInGames
            FROM Location L
            JOIN Session S ON L.LocationID = S.Team1ID OR L.LocationID = S.Team2ID
            WHERE S.StartDateTime BETWEEN '$start_date' AND '$end_date'
            GROUP BY L.LocationID
            HAVING COUNT(CASE WHEN S.Type = 'Game' THEN S.SessionID END) >= 2
            ORDER BY TotalGameSessions DESC;
        ";

        // Execute the query
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }
    } else {
        $result = null; 
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
            <li><a href="../emailLog/index.php">Email Logs</a></li>

            <!-- Reports Dropdown -->
            <li class="dropdown">
                <a href="#">Reports</a>
                <ul class="dropdown-content">
                    <li><a href="query9.php">Query 9</a></li>
                    <li><a href="query10.php">Query 10</a></li>
                    <li><a href="query11.php">Query 11</a></li>
                    <li><a href="query12.php">Query 12</a></li>
                    <li><a href="query13.php">Query 13</a></li>
                    <li><a href="query14.php">Query 14</a></li>
                    <li><a href="query15.php">Query 15</a></li>
                    <li><a href="query16.php">Query 16</a></li>
                    <li><a href="query17.php">Query 17</a></li>
                    <li><a href="query18.php">Query 18</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Main Section -->
    <main>
        <div class="list-container">
            <h2>Query 11: Info about teams with at least 2 game sessions between the periods of <?= htmlspecialchars($start_date)?> and <?= htmlspecialchars($end_date) ?></h2>
            <form method="GET" action="query11.php">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" required>
    
    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" required>
    
    <button type="submit">Submit</button>
</form>
<table class="data-table">
    <thead>
        <tr>
            <th>Location Name</th>
            <th>Total Training Sessions</th>
            <th>Total Players in Training</th>
            <th>Total Game Sessions</th>
            <th>Total Players in Games</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['LocationName']) ?></td>
                    <td><?= htmlspecialchars($row['TotalTrainingSessions']) ?></td>
                    <td><?= htmlspecialchars($row['TotalPlayersInTraining']) ?></td>
                    <td><?= htmlspecialchars($row['TotalGameSessions']) ?></td>
                    <td><?= htmlspecialchars($row['TotalPlayersInGames']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No results found or invalid date range.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
        </div>
    </main>
</body>