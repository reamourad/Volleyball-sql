<?php
    $page_title = "Add Family Members";
    require_once '../database.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Get values
        $firstName = mysqli_real_escape_string($conn, $_POST['first-name']);
        $lastName = mysqli_real_escape_string($conn, $_POST['last-name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $dob = !empty($_POST['dob']) ? mysqli_real_escape_string($conn, $_POST['dob']) : null;
        $sin = !empty($_POST['sin']) ? mysqli_real_escape_string($conn, $_POST['sin']) : null;
        $medicare = !empty($_POST['medicare-card']) ? mysqli_real_escape_string($conn, $_POST['medicare-card']) : null;
        $phone = mysqli_real_escape_string($conn, $_POST['telephone-number']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $province = mysqli_real_escape_string($conn, $_POST['province']);
        $postalCode = mysqli_real_escape_string($conn, $_POST['postal-code']);

        mysqli_begin_transaction($conn);

        try {
            //Insert into Person
            $personQuery = "
                INSERT INTO Person (SSN, FirstName, LastName, MedicareNumber, DateOfBirth, PhoneNumber, Address, City, Province, PostalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmt = mysqli_prepare($conn, $personQuery);
            mysqli_stmt_bind_param($stmt, 'ssssssssss', $sin, $firstName, $lastName, $medicare, $dob, $phone, $address, $city, $province, $postalCode);
        
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to add Person: " . mysqli_error($conn));
            }

            //Insert into FamilyMember
            $personID = mysqli_insert_id($conn);
            $familyQuery = "
                INSERT INTO FamilyMember (PersonID, Email) VALUES (?, ?)
            ";
            $stmt = mysqli_prepare($conn, $familyQuery);
            mysqli_stmt_bind_param($stmt, "is", $personID, $email);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to add FamilyMember: " . mysqli_error($conn));
            }

            mysqli_commit($conn);

            // Redirect with success parameter
            header("Location: add.php?success=1");
            exit;
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = $e->getMessage();
        }
    }
?>
<head>
    <title><?= $page_title ?></title>
    <link rel="stylesheet" type="text/css" href="../css/navbar.css">
    <link rel="stylesheet" type="text/css" href="../css/footer.css">
    <link rel="stylesheet" type="text/css" href="../css/forms.css">
    <script>
        // Check for success parameter and display alert
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                alert('Family member added successfully!');
            }
        };
    </script>
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

            <!-- Confirming the addition -->
            <?php if(isset($error)):?>
                <div class="error">Error: <? htmlspecialchars($error)?></div>
            <?php endif; ?>

            <form action="add.php" method="POST">
                <label for="first-name">First Name *:</label>
                <input type="text" name="first-name" id="first-name" required
                    value="<?= isset($_POST['first-name']) ? htmlspecialchars($_POST['first-name']) : '' ?>"
                >
                <br>
                <label for="last-name">Last Name *:</label>
                <input type="text" name="last-name" id="last-name" required
                    value="<?= isset($_POST['last-name']) ? htmlspecialchars($_POST['last-name']) : '' ?>"
                >
                <br>
                <label for="email">Email Address *:</label>
                <input type="email" name="email" id="email" required
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                >
                <br>
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob"
                    value="<?= isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : '' ?>"
                >
                <br>
                <label for="sin">SIN:</label>
                <input type="text" name="sin" id="sin"
                    value="<?= isset($_POST['sin']) ? htmlspecialchars($_POST['sin']) : '' ?>"
                >
                <br>
                <label for="medicare-card">Medicare Card Number:</label>
                <input type="text" name="medicare-card" id="medicare-card"
                    value="<?= isset($_POST['medicare-card']) ? htmlspecialchars($_POST['medicare-card']) : '' ?>"
                >
                <br>
                <label for="telephone-number">Telephone Number *:</label>
                <input type="text" name="telephone-number" id="telephone-number" required
                    value="<?= isset($_POST['telephone-number']) ? htmlspecialchars($_POST['telephone-number']) : '' ?>"
                >
                <br>
                <label for="address">Address *:</label>
                <input type="text" name="address" id="address" required
                    value="<?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?>"
                >
                <br>
                <label for="city">City *:</label>
                <input type="text" name="city" id="city" required
                    value="<?= isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '' ?>"
                >
                <br>
                <label for="province">Province *:</label>
                <input type="text" name="province" id="province" required
                    value="<?= isset($_POST['province']) ? htmlspecialchars($_POST['province']) : '' ?>"
                >
                <br>
                <label for="postal-code">Postal Code *:</label>
                <input type="text" name="postal-code" id="postal-code" required
                    value="<?= isset($_POST['postal-code']) ? htmlspecialchars($_POST['postal-code']) : '' ?>"
                >
                <br>
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Add Family Member</button>
            </form>
        </div>
    </main>
</body>