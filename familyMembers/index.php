<?php
    $page_title = "Family Members";
    require_once '../database.php';

    $query = "
        SELECT 
            p.PersonID,
            fm.isPrimary,
            p.FirstName,
            p.LastName,
            p.Email,
            p.PhoneNumber,
            p.MedicareNumber,
            p.DateOfBirth,
            p.Address,
            p.City,
            p.Province,
            p.PostalCode
        FROM
            FamilyMember fm
        JOIN
            Person p ON fm.PersonID = p.PersonID
        ORDER BY
            p.PersonID
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
            <h2>List of Family Members</h2>
            <button class="add-btn" onclick="window.location.href='add.php'">Add New Family Member</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Family Member ID</th>
                        <th>Type</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Medicare Number</th>
                        <th>Date of Birth</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Postal Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['PersonID']) ?></td>
                            <td><?= $row['isPrimary'] ? 'Primary' : 'Secondary' ?></td>
                            <td><?= htmlspecialchars($row['FirstName']) ?></td>
                            <td><?= htmlspecialchars($row['LastName']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                            <td><?= htmlspecialchars($row['MedicareNumber']) ?></td>
                            <td><?= htmlspecialchars($row['DateOfBirth']) ?></td>
                            <td><?= htmlspecialchars($row['Address']) ?></td>
                            <td><?= htmlspecialchars($row['City']) ?></td>
                            <td><?= htmlspecialchars($row['Province']) ?></td>
                            <td><?= htmlspecialchars($row['PostalCode']) ?></td>
                            <td class="action-links">
                                <a href="show-details.php?id=<?= $row['PersonID'] ?>">View</a>
                                <a href="edit.php?id=<?= $row['PersonID'] ?>">Edit</a>
                                <a href="delete.php?id=<?= $row['PersonID'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>