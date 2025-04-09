<?php
    $page_title = "Query 15";
    require_once '../database.php';

    $query = "
        SELECT
            p.FirstName,
            p.LastName,
            p.PhoneNumber
        FROM FamilyMember fm
        JOIN Person p ON fm.PersonID = p.PersonID
        JOIN Personnel per ON per.EmployeeID = fm.PersonID
        JOIN Contract c ON c.EmployeeID = per.EmployeeID
        JOIN ClubMember cm ON cm.PrimaryFamilyID = fm.PersonID OR cm.AlternativeFamilyID = fm.PersonID
        JOIN Payment pay ON cm.CMN = pay.CMN
        JOIN Location l ON c.LocationID = l.LocationID
        WHERE l.LocationID = 1
        AND c.EndDate >= CURDATE()
        AND c.Role = 'Captain'
        AND pay.MembershipEndDate >= CURDATE()
        -- captain are in a formation session at this location
        AND EXISTS (
            SELECT 1
            FROM Team t
            JOIN Session s ON s.Team1ID = t.TeamID OR s.Team2ID = t.TeamID
            WHERE t.Captain = fm.PersonID
            AND s.Address = l.Address
        )
        GROUP BY fm.PersonID
        ORDER BY l.Name, fm.PersonID;
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
            <h2>Query 15: List of Captains with Active Related Members at Their Location</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['FirstName']) ?></td>
                            <td><?= htmlspecialchars($row['LastName']) ?></td>
                            <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>