<?php
    $page_title = "Personnel Details";
    require_once '../database.php';

    //Get the employee ID from the URL parameter
    $employeeID = isset($_GET['id']) ? $_GET['id'] : 0;
    $employeeName = "";

    //Get the employee name for the title
    if($employeeID > 0) {
        $query = "SELECT CONCAT(FirstName, ' ', LastName) AS FullName FROM Person WHERE PersonID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $employeeID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $employeeRow = mysqli_fetch_assoc($result);
        $employeeName = $employeeRow['FullName'];
    }
    

    $query = "
        SELECT 
            c.Role,
            c.LocationID,
            l.Name AS LocationName,
            p.Mandate AS Type,
            CASE
                WHEN EndDate IS NULL AND c.StartDate <= CURDATE() THEN 'Active'
                WHEN c.StartDate <= CURDATE() AND c.EndDate >= CURDATE() THEN 'Active'
                WHEN c.StartDate > CURDATE() THEN 'Future'
                ELSE 'Inactive'
            END AS Status,
            c.StartDate,
            c.EndDate
        FROM 
            Contract c
        JOIN 
            Location l ON c.LocationID = l.LocationID
        JOIN 
            Personnel p ON c.EmployeeID = p.EmployeeID
        WHERE
            c.EmployeeID = ?
        ORDER BY 
            Status, c.StartDate DESC
    ";

    // Execute the query
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $employeeID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

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
            <h2>Contract for <?= htmlspecialchars($employeeName) ?></h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Location ID</th>
                        <th>Location Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Role']) ?></td>
                            <td><?= htmlspecialchars($row['LocationID']) ?></td>
                            <td><?= htmlspecialchars($row['LocationName']) ?></td>
                            <td><?= htmlspecialchars($row['Type']) ?></td>
                            <td><?= htmlspecialchars($row['Status']) ?></td>
                            <td><?= htmlspecialchars($row['StartDate']) ?></td>
                            <td><?= htmlspecialchars($row['EndDate']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>