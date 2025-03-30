<?php
    function displayFamilyMember(){
        require '../database.php';
        $query = "";
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
                <a href="#">Family Members</a>
                <ul class="dropdown-content">
                    <li><a href="familyMembers\index.php">Family Member List</a></li>
                    <li><a href="#">Detailed Family Member</a></li>
                </ul>
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
            <h2>List of Members</h2>
            <table class="data-table">
            <thead>
                <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone Number</th>
                <th>Location</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>John</td>
                    <td>Doe</td>
                    <td>(123) 456-7890</td>
                    <td>New York</td>
                    <td><a href="#">View</a> | <a href="#">Edit</a> | <a href="#">Delete</a></td>
                </tr>
                <tr>
                    <td>Jane</td>
                    <td>Smith</td>
                    <td>(987) 654-3210</td>
                    <td>Los Angeles</td>
                    <td><a href="#">View</a> | <a href="#">Edit</a> | <a href="#">Delete</a></td>
                </tr>
                <!-- Displayed dynamically -->
                
            </tbody>
            </table>
        </div>
    </main>
</body>