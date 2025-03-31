<?php
    $page_title = "Family Members";

    function displayFamilyMember(){
        require '../database.php';
        $query = "";
        //todo: Implement PHP code to display family members
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
            <h2>List of Members</h2>
            <button class="add-btn" onclick="window.location.href='add.php'">Add New Club Member</button>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>CMN</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Date of Birth</th>
                        <th>Height</th>
                        <th>Weight</th>
                        <th>SIN</th>
                        <th>Medicare Card Number</th>
                        <th>Telephone Number</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Postal Code</th>
                        <th>Family Member</th>
                        <th>Actions</th>
                    </tr>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John</td>
                        <td>Doe</td>
                        <td>1990-01-01</td>
                        <td>5'10"</td>
                        <td>180 lbs</td>
                        <td>123 456 789</td>
                        <td>123 456 789</td>
                        <td>123 456 7890</td>
                        <td>1234 Street Name</td>
                        <td>City</td>
                        <td>Province</td>
                        <td>A1A 1A1</td>
                        <td>Family Member</td>
                        <td><a href="edit.php">Edit</a> | <a href="#">Delete</a></td>
                    </tr>
                    <!-- Displayed dynamically -->
                    <!-- 
                        //Todo: Implement PHP code to display club members
                    -->
                    
                </tbody>
            </table>
        </div>
    </main>
</body>