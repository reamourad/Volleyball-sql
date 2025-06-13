<?php
    $page_title = "Query 9";
    require_once '../database.php';

  


    // Get the week and location values from the form
    $week = isset($_GET['week']) ? $_GET['week'] : null;
    $location = isset($_GET['location']) ? $_GET['location'] : null;

    //checking for validity of week and loc data
    if ($week && $location) {
        
        $location_query = "SELECT COUNT(*) AS LocationExists FROM Location WHERE Name = '$location'";
        $location_result = mysqli_query($conn, $location_query);
        $location_row = mysqli_fetch_assoc($location_result);

        if ($location_row['LocationExists'] > 0) {
            
            // Calculate the start and end dates of the selected week
            $start_date = date('Y-m-d', strtotime($week));
            $end_date = date('Y-m-d', strtotime($week . ' +6 days'));

           
            $query = "
                SELECT 
                    Person.FirstName AS HeadCoachFirstName,
                    Person.LastName AS HeadCoachLastName,
                    S.StartDateTime AS SessionStartTime,
                    S.Address AS SessionAddress,
                    S.Type AS SessionType,
                    T.TeamName AS TeamName,
                    S.Score1 AS Team1Score,
                    S.Score2 AS Team2Score,
                    Player.FirstName AS PlayerFirstName,
                    Player.LastName AS PlayerLastName,
                    R.Position AS PlayerRole
                FROM Session S
                JOIN Personnel P ON S.HeadCoachID = P.EmployeeID
                JOIN Person ON P.EmployeeID = Person.PersonID 
                JOIN Team T ON T.TeamID = S.Team1ID OR T.TeamID = S.Team2ID
                JOIN Role R ON R.TeamID = T.TeamID
                JOIN ClubMember CM ON CM.CMN = R.CMN
                JOIN Person Player ON Player.PersonID = CM.PersonID
                JOIN Location L ON L.LocationID = T.LocationID
                WHERE L.Name = '$location' 
                AND S.StartDateTime BETWEEN '$start_date' AND '$end_date' 
                ORDER BY DATE(S.StartDateTime) ASC, TIME(S.StartDateTime) ASC;
            ";

            // Execute the query
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }
        } else {
            $result = null; 
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
            <h2>Query 9: Session Details for <?= htmlspecialchars($week) ?>  at <?= htmlspecialchars($location) ?>  </h2>
            <form method="GET" action="query9.php">
    <label for="week">Select a Week:</label>
    <input type="date" id="week" name="week" required>
    <label for="location">Enter the Location Name:</label>
    <input type="text" id="location" name="location" required>
    <button type="submit">Submit</button>
</form>
            <h3>Results for Week: <?= htmlspecialchars($week) ?>, Location: <?= htmlspecialchars($location) ?></h3>

<table class="data-table">
    <thead>
        <tr>
            <th>Head Coach First Name</th>
            <th>Head Coach Last Name</th>
            <th>Session Start Time</th>
            <th>Session Address</th>
            <th>Session Type</th>
            <th>Team Name</th>
            <th>Team 1 Score</th>
            <th>Team 2 Score</th>
            <th>Player First Name</th>
            <th>Player Last Name</th>
            <th>Player Role</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['HeadCoachFirstName']) ?></td>
                    <td><?= htmlspecialchars($row['HeadCoachLastName']) ?></td>
                    <td><?= htmlspecialchars($row['SessionStartTime']) ?></td>
                    <td><?= htmlspecialchars($row['SessionAddress']) ?></td>
                    <td><?= htmlspecialchars($row['SessionType']) ?></td>
                    <td><?= htmlspecialchars($row['TeamName']) ?></td>
                    <td><?= htmlspecialchars($row['Team1Score']) ?></td>
                    <td><?= htmlspecialchars($row['Team2Score']) ?></td>
                    <td><?= htmlspecialchars($row['PlayerFirstName']) ?></td>
                    <td><?= htmlspecialchars($row['PlayerLastName']) ?></td>
                    <td><?= htmlspecialchars($row['PlayerRole']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="11">No results found for the selected week or location.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
        </div>
    </main>
</body>