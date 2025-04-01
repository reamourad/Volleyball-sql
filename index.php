<?php
    $page_title = "Home";
?>

<head>
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="css/footer.css"/>
    <link rel="stylesheet" href="css/navbar.css"/>
    <link rel="stylesheet" href="css/home.css"/>
</head>
<body>
    <!-- Navbar Section -->
    <nav>
        <h2>MYVC Management System</h2>
        <ul>
            <li><a href="index.php">Home</a></li>
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
                <a href="familyMembers/index.php">Family Members</a>
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
        <div class="left-container">
            <h1>Developing Future Volleyball Stars</h1>
            <h3>
                Montréal Youth Volleyball Club (MYVC) is dedicated to developing, promoting, and enhancing youth volleyball across different 
                areas of Montréal. We provide long-term services to help young athletes aged 11-18 become professional volleyball players.
            </h3>
            <p>
                Our club offers comprehensive volleyball programs through our head location and multiple branches across the region. Players 
                join either boys or girls teams and receive professional coaching to develop their skills. The club maintains detailed records 
                of each member's progress, skills development, and participation in games and training sessions.
                <br><br>
                MYVC emphasizes teamwork, discipline, and sportsmanship while helping young athletes reach their full potential. Our system tracks 
                all aspects of club operations including member registration, team formations, game schedules, and financial records to ensure 
                smooth operations and excellent member experiences.
                <br><br>
                Family involvement is key to our success, with at least one registered family member required for each youth member. Our system 
                maintains all family relationships and contact information to keep everyone informed and engaged with club activities.
            </p>
        </div>

        <div class="right-container"> 
            <h1>MYVC Club Management System</h1>
            <h3>
                SYSTEM PURPOSE:<br>
                Develop a comprehensive database system to manage all aspects of the Montréal Youth Volleyball Club operations, from member registration 
                to game scheduling and performance tracking.
            </h3>
            <p>
                The application maintains detailed information about club locations, personnel, family members, and club members. It tracks team formations, 
                game schedules, player roles, and scores. The system automatically handles membership renewals, sends notifications about upcoming sessions, 
                and manages the deactivation process when members age out of the program.
                <br><br>
                Financial records are meticulously maintained, including membership fees and payments. The system ensures compliance with all club rules, such 
                as age restrictions, team composition requirements, and scheduling constraints to prevent player conflicts.
            </p>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <h3>Link to the <a href="#">report</a></h3>
            <p>
                Réa Mourad (40310288) <br>
                Elizabeth O'Meara (40065959) <br>
                Amani-Myriam Maamar (40191681) <br>
                Anh Thy Vu (40270849) <br>
                Yani Zahouani (40285973)
            </p>
        </div>
    </footer>
</body>
</html>