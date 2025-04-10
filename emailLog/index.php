<?php
    $page_title = "Email Logs";
    require_once '../database.php';

    $query = "
        SELECT 
            l.Name AS LocationName,
            CONCAT(p.FirstName, ' ', p.LastName) AS FullName,
            e.Subject,
            DATE(e.Date) AS Date, 
            DATE_FORMAT(e.Date, '%h:%i %p') AS Time,
            e.First100Chars AS Body
        FROM
            Email e
        JOIN
            Person p ON e.RecipientID = p.PersonID
        JOIN
            Location l ON e.LocationID = l.LocationID
        ORDER BY
            e.Date DESC
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
            <h2>Email Log</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Sent to</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Body</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['LocationName']) ?></td>
                            <td><?= htmlspecialchars($row['FullName']) ?></td>
                            <td><?= htmlspecialchars($row['Subject']) ?></td>
                            <td><?= htmlspecialchars($row['Date']) ?></td>
                            <td><?= htmlspecialchars($row['Time']) ?></td>
                            <td><?= htmlspecialchars($row['Body']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>