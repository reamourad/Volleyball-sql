<?php
    $page_title = "Query 15";
    require_once '../database.php';

    // Get the location name from the form
    $location = isset($_GET['location']) ? trim($_GET['location']) : null;

    $result = null; 

    if ($location) {
       
        $stmt = $conn->prepare("
            SELECT DISTINCT 
                captain.FirstName AS CaptainFirstName, 
                captain.LastName AS CaptainLastName, 
                captain.PhoneNumber
            FROM FamilyMember fm
            JOIN Person captain ON fm.PersonID = captain.PersonID
            JOIN Team t ON t.Captain = fm.PersonID
            JOIN ClubMember cm ON (cm.PrimaryFamilyID = fm.PersonID OR cm.AlternativeFamilyID = fm.PersonID)
            JOIN Location l ON t.LocationID = l.LocationID
            WHERE (fm.isPrimary = TRUE OR cm.AlternativeFamilyID IS NOT NULL)
            AND l.Name = ?
        ");

        //take location param and bind it to the query
        if ($stmt) {
            
            $stmt->bind_param("s", $location);

            
            $stmt->execute();
            $result = $stmt->get_result();

            
            if (!$result) {
                die("Query failed: " . $stmt->error);
            }

           
            $stmt->close();
        } else {
            die("Failed to prepare the query: " . $conn->error);
        }
    }
?>

<head>
    <title><?= htmlspecialchars($page_title) ?></title>
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
        <h2>Query 15: List of Captains with Active Related Members at <?= htmlspecialchars($location) ?: 'the Specified Location' ?></h2>
            <form method="GET" action="query15.php">
                <label for="location">Enter Location Name:</label>
                <input type="text" id="location" name="location" required>
                <button type="submit">Search</button>
            </form>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['CaptainFirstName']) ?></td>
                                <td><?= htmlspecialchars($row['CaptainLastName']) ?></td>
                                <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No results found for the specified location.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>