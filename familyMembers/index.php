<?php
    $page_title = "Family Members";
    require_once '../database.php';

    $query = "
        SELECT 
            p.PersonID,
            p.FirstName,
            p.LastName,
            fm.Email,
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
            <!-- Club Members Dropdown -->
            <li class="dropdown">
                <a href="#">Club Members</a>
                <ul class="dropdown-content">
                    <li><a href="#">Club Member List</a></li>
                    <li><a href="#">Team Formation</a></li>
                </ul>
            </li>

            <!-- Family Members Dropdown -->
            <li class="dropdown">
                <a href="index.php">Family Members</a>
            </li>

            <!-- Personnel Dropdown -->
            <li class="dropdown">
                <a href="#">Personnel</a>
                <ul class="dropdown-content">
                    <li><a href="#">Personnel List</a></li>
                </ul>
            </li>

            <!-- Locations Dropdown -->
            <li class="dropdown">
                <a href="#">Locations</a>
                <ul class="dropdown-content">
                    <li><a href="#">Location Lists</a></li>
                    <li><a href="#">Events at Location</a></li>
                </ul>
            </li>

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
            <h2>List of Family Members</h2>
            <button class="add-btn" onclick="window.location.href='add.php'">Add New Family Member</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Family Member ID</th>
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