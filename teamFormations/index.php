<?php
    $page_title = "Family Members";

    function displayFamilyMember(){
        require '../database.php';
        $query = "";
        //todo: Implement PHP code to display teams
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
                        <th>Team Name</th>
                        <th>Coach</th>
                        <th>Captain</th>
                        <th>Location</th>
                        <th># of Players</th>
                        <th>Actions</th>
                    </tr>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Team 1</td>
                        <td>Coach 1</td>
                        <td>Captain 1</td>
                        <td>Location 1</td>
                        <td>10</td>
                        <td><a href="show-details.php">Show Details</a></td>
                    </tr>
                    <!-- Displayed dynamically -->
                    <!-- 
                        //Todo: Implement PHP code to display teams
                    -->
                    
                </tbody>
            </table>
        </div>
    </main>
</body>