<?php
    $page_title = "Family Members";

    function displayFamilyMember(){
        require '../database.php';
        $query = "";
        //todo: Implement PHP code to display team 
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
            <li><a href="index.php">Club Members</a></li>
            <li><a href="familyMembers/index.php">Family Members</a></li>
            <li><a href="personnels/index.php">Personnel</a></li>
            <li><a href="locations/index.php">Locations</a></li>
            <li><a href="teamFormations/index.php">Team Formation</a></li>
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
            <!-- 
                //todo: Implement PHP code to display team name
            -->
            <h2>"Team Name"</h2>
            <button class="add-btn" onclick="window.location.href='add.php'">Add Player</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>CMN</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Position</th>
                        <th>Age</th>
                        <th>Phone #</th>
                        <th>Email</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John</td>
                        <td>Doe</td>
                        <td>Libero</td>
                        <td>25</td>
                        <td>123-456-7890</td>
                        <td>john.doe@email.com</td>
                        <td>Location 1</td>
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