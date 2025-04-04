<?php
    $page_title = "Team Formation";
    require_once "../database.php";

    $query = "
        SELECT
            t.TeamID,
            t.TeamName,
            t.Gender,
            t.LocationID,
            l.Name AS LocationName,
            t.Captain AS CaptainID,
            CONCAT(p.FirstName, ' ', p.LastName) AS CaptainName,
            (SELECT COUNT(*) FROM Role r WHERE r.TeamID = t.TeamID) AS PlayerCount
        FROM
            Team t
        LEFT JOIN Location l ON t.LocationID = l.LocationID
        LEFT JOIN ClubMember cm ON t.Captain = cm.CMN
        LEFT JOIN Person p ON cm.PersonID = p.PersonID
        ORDER BY
            t.TeamID
    ";
    
    $result = mysqli_query($conn, $query);
    $teams = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
            <li><a href="index.php">Team Formation</a></li>
            <li><a href="#">Events</a></li>

            <!-- Email Logs Dropdown -->
            <li class="dropdown">
                <a href="#">Email Logs</a>
                <ul class="dropdown-content">
                    <li><a href="#">Subcategory 1</a></li>
                    <li><a href="#">Subcategory 2</a></li>
                    <li><a href="#">Subcategory 3</a></li>
                </ul>
            </li>

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
        <div class="list-container">
            <h2>List of Teams</h2>
            <button class="add-btn" onclick="window.location.href='add.php'">Add Team</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Team ID</th>
                        <th>Team Name</th>
                        <th>Gender</th>
                        <th>Location ID</th>
                        <th>Location Name</th>
                        <th>Captain ID</th>
                        <th>Captain Name</th>
                        <th># of Players</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teams as $team): ?>
                        <tr>
                            <td><?= htmlspecialchars($team['TeamID']) ?></td>
                            <td><?= htmlspecialchars($team['TeamName']) ?></td>
                            <td><?= htmlspecialchars($team['Gender']) ?></td>
                            <td><?= htmlspecialchars($team['LocationID']) ?></td>
                            <td><?= htmlspecialchars($team['LocationName'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($team['CaptainID']) ?></td>
                            <td><?= htmlspecialchars($team['CaptainName'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($team['PlayerCount']) ?></td>
                            <td>
                                <a href="show-details.php?id=<?= $team['TeamID'] ?>">View</a>
                                <a href="edit.php?id=<?= $team['TeamID'] ?>" class="edit-btn">Edit</a>
                                <a href="delete.php?id=<?= $team['TeamID'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this team?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>