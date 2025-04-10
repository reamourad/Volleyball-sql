<?php
    $page_title = "Query 15";
    require_once '../database.php';

    $query = "
    SELECT DISTINCT 
        captain.FirstName AS CaptainFirstName, 
        captain.LastName AS CaptainLastName, 
        captain.PhoneNumber
    FROM FamilyMember fm
    JOIN Person captain ON fm.PersonID = captain.PersonID
    JOIN Team t ON t.Captain = fm.PersonID
    JOIN ClubMember cm ON (cm.PrimaryFamilyID = fm.PersonID OR cm.AlternativeFamilyID = fm.PersonID)
    JOIN Person player ON cm.PersonID = player.PersonID
    JOIN Location l ON t.LocationID = l.LocationID AND cm.LocationID = l.LocationID
    WHERE fm.isPrimary = TRUE OR cm.AlternativeFamilyID IS NOT NULL;
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
            <h2>Query 15: List of Captains with Active Related Members at Their Location</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                    </tr>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['CaptainFirstName']) ?></td>
                            <td><?= htmlspecialchars($row['CaptainLastName']) ?></td>
                            <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>