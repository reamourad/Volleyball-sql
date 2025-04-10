<?php
    $page_title = "Query 16";
    require_once '../database.php';

    $query = "
        SELECT
    cm.CMN,
    p.FirstName,
    p.LastName,
    FLOOR(DATEDIFF(CURDATE(), p.DateOfBirth)/365) AS age, 
    p.PhoneNumber,
    p.Email,
    l.Name AS LocationName
FROM 
    ClubMember cm
    JOIN Person p ON cm.PersonID = p.PersonID
    JOIN Location l ON cm.LocationID = l.LocationID
    JOIN Payment pay ON cm.CMN = pay.CMN
WHERE 
    -- Member is active
    pay.MembershipEndDate >= CURDATE()

    --  has been assigned to at least one formation game session
    AND EXISTS (
        SELECT 1
        FROM Role r
        JOIN Team t ON r.TeamID = t.TeamID
        JOIN Session s ON (s.Team1ID = t.TeamID OR s.Team2ID = t.TeamID)
        WHERE r.CMN = cm.CMN
    )

    -- has never lost a game they played in
    AND NOT EXISTS (
        SELECT 1
        FROM Role r
        JOIN Team t ON r.TeamID = t.TeamID
        JOIN Session s ON (s.Team1ID = t.TeamID OR s.Team2ID = t.TeamID)
        WHERE r.CMN = cm.CMN
        AND (
            (t.TeamID = s.Team1ID AND s.Score1 < s.Score2 AND s.Score1 IS NOT NULL AND s.Score2 IS NOT NULL) 
            OR
            (t.TeamID = s.Team2ID AND s.Score2 < s.Score1 AND s.Score1 IS NOT NULL AND s.Score2 IS NOT NULL)
        )
        AND s.Type = 'Game'
        )
        GROUP BY cm.CMN
        ORDER BY 
            l.Name ASC, 
            cm.CMN ASC;
    ";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if (!$result) {
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
            <h2>Query 16: List of Undefeated Players</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>CMN</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Age</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Location Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['CMN']) ?></td>
                            <td><?= htmlspecialchars($row['FirstName']) ?></td>
                            <td><?= htmlspecialchars($row['LastName']) ?></td>
                            <td><?= htmlspecialchars($row['age']) ?></td>
                            <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td><?= htmlspecialchars($row['LocationName']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>