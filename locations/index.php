<?php
    $page_title = "Locations";
    require_once '../database.php';

    $query = "
        SELECT *
        FROM Location
        ORDER BY LocationID
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
            <li><a href="index.php">Locations</a></li>
            <li><a href="../teamFormations/index.php">Team Formation</a></li>
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
            <h2>List of Locations</h2>
            <button class="add-btn" onclick="window.location.href='add.php'">Add New Location</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Location ID</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Postal Code</th>
                        <th>Phone Number</th>
                        <th>Website</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['LocationID']) ?></td>
                            <td><?= htmlspecialchars($row['Type']) ?></td>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Address']) ?></td>
                            <td><?= htmlspecialchars($row['City']) ?></td>
                            <td><?= htmlspecialchars($row['Province']) ?></td>
                            <td><?= htmlspecialchars($row['PostalCode']) ?></td>
                            <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                            <td><?= htmlspecialchars($row['Website']) ?></td>
                            <td><?= htmlspecialchars($row['Capacity']) ?></td>

                            <td class="action-links">
                                <a href="show-mandates.php?id=<?= $row['LocationID'] ?>">View</a>
                                <a href="edit.php?id=<?= $row['LocationID'] ?>">Edit</a>
                                <a href="delete.php?id=<?= $row['LocationID'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>