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
            <li><a href="clubMembers/index.php">Club Members</a></li>
            <li><a href="familyMembers/index.php">Family Members</a></li>
            <li><a href="personnels/index.php">Personnel</a></li>
            <li><a href="locations/index.php">Locations</a></li>
            <li><a href="teamFormations/index.php">Team Formation</a></li>
            <li><a href="emailLog/index.php">Email Logs</a></li>

            <!-- Reports Dropdown -->
            <li class="dropdown">
                <a href="#">Queries</a>
                <ul class="dropdown-content">
                    <li><a href="queries/query9.php">Query 9</a></li>
                    <li><a href="queries/query10.php">Query 10</a></li>
                    <li><a href="queries/query11.php">Query 11</a></li>
                    <li><a href="queries/query12.php">Query 12</a></li>
                    <li><a href="queries/query13.php">Query 13</a></li>
                    <li><a href="queries/query14.php">Query 14</a></li>
                    <li><a href="queries/query15.php">Query 15</a></li>
                    <li><a href="queries/query16.php">Query 16</a></li>
                    <li><a href="queries/query17.php">Query 17</a></li>
                    <li><a href="queries/query18.php">Query 18</a></li>
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
            <h3>Link to the <a href="https://docs.google.com/document/d/1pUyRRn5kpI1VIzMGPuvLELThp76WNGC2LXaYYmLUe3M/edit?tab=t.0#heading=h.ry124sf7v27n" target="_blank">report</a></h3>
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