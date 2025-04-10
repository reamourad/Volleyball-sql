<?php
    $page_title = "Sessions";
    require_once "../database.php";

    $query = "
        SELECT s.SessionID,
                s.StartDateTime,
                s.Type,
                CONCAT(p.FirstName, ' ', p.LastName) AS HeadCoach,
                t1.TeamName AS Team1,
                s.Score1,
                s.Score2,
                t2.TeamName AS Team2,
                s.Address
        FROM Session s
        JOIN Team t1 ON s.Team1ID=t1.TeamID
        JOIN Team t2 ON s.Team2ID=t2.TeamID
        JOIN Person p ON s.HeadCoachID=p.PersonID
        ORDER BY s.StartDateTime, s.Type;
    ";
    
    $result = mysqli_query($conn, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
            <li><a href="sessions/index.php">Sessions</a></li>
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
            <h2>List of Scheduled Sessions</h2>
            <button class="add-btn" onclick="window.location.href='create-session.php'">Add Session</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Session Date and Time</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Head Coach</th>
                        <th>Team 1</th>
                        <th>Team 1 Score</th>
                        <th>Team 2 Score</th>
                        <th>Team 2</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['StartDateTime']) ?></td>
                            <td><?= htmlspecialchars($row['Address']) ?></td>
                            <td><?= htmlspecialchars($row['Type']) ?></td>
                            <td><?= htmlspecialchars($row['HeadCoach']) ?></td>
                            <td><?= htmlspecialchars($row['Team1']) ?></td>
                            <td><?= htmlspecialchars($row['Score1']) ?></td>
                            <td><?= htmlspecialchars($row['Score2']) ?></td>
                            <td><?= htmlspecialchars($row['Team2']) ?></td>
                            <td>
                                <a href="edit-session.php?id=<?= $row['SessionID'] ?>" class="edit-btn">Edit</a>
                                <a href="delete-session.php?id=<?= $row['SessionID'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to cancel this session?')">Cancel</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>