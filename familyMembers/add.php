<?php
    $page_title = "Add Family Members";

    //TODO: Include the query for the addition of family members
?>

<head>
    <title><?= $page_title ?></title>
    <link rel="stylesheet" type="text/css" href="../css/navbar.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../css/forms.css">
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
        <div class="form-container">
            <h1>Add New Family Members</h1>
            <form action="add.php" method="POST">
                <label for="first-name">First Name:</label>
                <input type="text" name="first-name" id="first-name" required>
                <br>
                <label for="last-name">Last Name:</label>
                <input type="text" name="last-name" id="last-name" required>
                <br>
                <label for="email">Email Address:</label>
                <input type="email" name="email" id="email" required>
                <br>
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" required>
                <br>
                <label for="sin">SIN:</label>
                <input type="text" name="sin" id="sin" required>
                <br>
                <label for="medicare-card">Medicare Card Number:</label>
                <input type="text" name="medicare-card" id="medicare-card" required>
                <br>
                <label for="telephone-number">Telephone Number:</label>
                <input type="text" name="telephone-number" id="telephone-number" required>
                <br>
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required>
                <br>
                <label for="city">City:</label>
                <input type="text" name="city" id="city" required>
                <br>
                <label for="province">Province:</label>
                <input type="text" name="province" id="province" required>
                <br>
                <label for="postal-code">Postal Code:</label>
                <input type="text" name="postal-code" id="postal-code" required>
                <br>
                <div class="checkbox-container">
                <label for="locations">Locations:</label>
                    <input type="checkbox" value="location1"> Location 1
                    <input type="checkbox" value="location2"> Location 2
                    <input type="checkbox" value="location3"> Location 3
                    <!-- Display the options dynamically -->

                    <!-- 
                        //TODO: Implement the dynamic display 
                    -->
                </div>
                <br>
                <button type="submit">Add Family Member</button>
            </form>
        </div>
    </main>
</body>