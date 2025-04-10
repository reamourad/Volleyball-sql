<?php
    $page_title = "Query 18";
    require_once '../database.php';

    $query = "
        DROP VIEW IF EXISTS DeactivatedMembers, TeamsLastSession;

        CREATE VIEW DeactivatedMembers AS
        SELECT
            c.CMN,
            p.FirstName,
            p.LastName,
            p.PhoneNumber,
            p.Email,
            DATE_ADD(LAST_DAY(DATE_ADD(p.DateOfBirth, INTERVAL 18 YEAR)),
                    INTERVAL 1 DAY) AS DeactivationDate,
            c.LocationID
        FROM Person p
        JOIN ClubMember c ON c.PersonID = p.PersonID
        JOIN Location l ON c.LocationID = l.LocationID
        WHERE p.DateOfBirth <= DATE_SUB(CURDATE(), INTERVAL 18 YEAR);

        CREATE VIEW TeamsLastSession AS
        SELECT t.TeamID, 
                max(s.StartDateTime) AS LastSession
        FROM Team t, Session s
        WHERE t.TeamID=s.Team1ID OR t.TeamID=s.Team2ID
        GROUP BY t.TeamID;

        SELECT DISTINCT
            dm.FirstName,
            dm.LastName,
            dm.PhoneNumber,
            dm.Email,
            dm.DeactivationDate,
            l.Name AS LocationName,
            r.Position
        FROM DeactivatedMembers dm
        JOIN Role r ON dm.CMN = r.CMN
        JOIN TeamsLastSession tls ON r.TeamID = tls.TeamID
        JOIN (
            SELECT r2.CMN, MAX(tls2.LastSession) AS MaxSession
            FROM Role r2
            JOIN TeamsLastSession tls2 ON r2.TeamID = tls2.TeamID
            GROUP BY r2.CMN
        ) latest ON r.CMN = latest.CMN AND tls.LastSession = latest.MaxSession
        JOIN Location l ON dm.LocationID = l.LocationID
        ORDER BY 
            l.Name,
            r.Position,
            dm.FirstName,
            dm.LastName;
    ";

    // Execute the query (multiple queries)
    if (mysqli_multi_query($conn, $query)) {
        // go through each result set, skip view creation results
        do {
            if ($result = mysqli_store_result($conn)) {
                // SELECT result
                break;
            }
        } while (mysqli_next_result($conn));
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
    

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
            <li><a href="../emailLog/index.php">Email Logs</a></li>

            <!-- Reports Dropdown -->
            <li class="dropdown">
                <a href="#">Queries</a>
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
            <h2>Query 18: Deactivated Members Over 18</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Deactivation Date</th>
                        <th>Last Location</th>
                        <th>Last Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['FirstName']) ?></td>
                            <td><?= htmlspecialchars($row['LastName']) ?></td>
                            <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td><?= htmlspecialchars($row['DeactivationDate']) ?></td>
                            <td><?= htmlspecialchars($row['LocationName']) ?></td>
                            <td><?= htmlspecialchars($row['Position']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>