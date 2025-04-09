<?php
    $page_title = "Query 13";
    require_once '../database.php';

    $query = "
        SELECT 
            ClubMember.CMN, 
            Person.FirstName, 
            Person.LastName, 
            FLOOR(DATEDIFF(CURDATE(), Person.DateOfBirth)/365) AS age, 
            Person.PhoneNumber, 
            Person.Email,
            Location.name AS location_name
        FROM Person, ClubMember, Location, Payment
        WHERE 
            -- joins 
            Person.PersonID = ClubMember.PersonID 
            AND Location.LocationID = ClubMember.LocationID 
            AND ClubMember.CMN = Payment.CMN 
            -- club member are still active 
            AND Payment.MembershipEndDate  >= CURDATE()
            AND FLOOR(DATEDIFF(CURDATE(), Person.DateOfBirth)/365) <= 18
            -- they're in a session as an outside hitter 
            AND EXISTS (
                SELECT 1 
                FROM Role r1
                JOIN Session ON r1.TeamID = Session.Team1ID OR r1.TeamID = Session.Team2ID
                WHERE r1.CMN = ClubMember.CMN 
                AND r1.Position = 'Outside Hitter'
            )
            -- they should never be assigned any other role in any session
            AND NOT EXISTS (
                SELECT 1 
                FROM Role r2
                JOIN Session ON r2.TeamID = Session.Team1ID OR r2.TeamID = Session.Team2ID
                WHERE r2.CMN = ClubMember.CMN 
                AND r2.Position != 'Outside Hitter'
            )
            
        GROUP BY ClubMember.CMN
        ORDER BY 
            location_name ASC, 
            ClubMember.CMN ASC
    
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
            <h2>Query 13: List of Active Club Members Exclusively Assigned as Outside Hitters in All Formation</h2>
            <table class="data-table">
                <thead>
                    <th>CMN</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Age</th>
                    <th>Phone #</th>
                    <th>Email</th>
                    <th>Location Name</th>
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
                            <td><?= htmlspecialchars($row['location_name']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>