<?php
    $page_title = "Query 12";
    require_once '../database.php';

    $query = "
        SELECT 
            CM.CMN AS MembershipNumber,
            P.FirstName,
            P.LastName,
            TIMESTAMPDIFF(YEAR, P.DateOfBirth, CURDATE()) AS Age,
            MIN(RA.DateRegistered) AS DateOfJoining,
            P.PhoneNumber,
            P.Email,
            L.Name AS CurrentLocationName
        FROM ClubMember CM
        JOIN Person P ON CM.PersonID = P.PersonID
        JOIN RegisteredAt RA ON CM.PrimaryFamilyID = RA.FamilyID
        JOIN Location L ON CM.LocationID = L.LocationID
        WHERE CM.CMN NOT IN (
            SELECT DISTINCT R.CMN
            FROM Role R
        )
        GROUP BY CM.CMN, P.FirstName, P.LastName, P.DateOfBirth, P.PhoneNumber, P.Email, L.Name
        ORDER BY L.Name ASC, CM.CMN ASC;
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
            <h2>Query 12</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Membership Number</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Age</th>
                        <th>Date of Joining</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Current Location Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['MembershipNumber']) ?></td>
                            <td><?= htmlspecialchars($row['FirstName']) ?></td>
                            <td><?= htmlspecialchars($row['LastName']) ?></td>
                            <td><?= htmlspecialchars($row['Age']) ?></td>
                            <td><?= htmlspecialchars($row['DateOfJoining']) ?></td>
                            <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td><?= htmlspecialchars($row['CurrentLocationName']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>