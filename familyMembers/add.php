<?php
    $page_title = "Add Family Members";
    require_once '../database.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Get values
        $isPrimary = ($_POST['family-type'] === 'primary') ? 1 : 0;
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
                INSERT INTO Person (SSN, FirstName, LastName, MedicareNumber, DateOfBirth, Email, PhoneNumber, Address, City, Province, PostalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmt = mysqli_prepare($conn, $personQuery);
            mysqli_stmt_bind_param($stmt, 'issssssssss', $sin, $firstName, $lastName, $medicare, $dob, $email, $phone, $address, $city, $province, $postalCode);
        
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to add Person: " . mysqli_error($conn));
            }

            //Insert into FamilyMember
            $personID = mysqli_insert_id($conn);
            $familyQuery = "
                INSERT INTO FamilyMember (PersonID, isPrimary) VALUES (?, ?)
            ";
            $stmt = mysqli_prepare($conn, $familyQuery);
            mysqli_stmt_bind_param($stmt, "ii", $personID, $isPrimary);

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
            <li><a href="../clubMembers/index.php">Club Members</a></li>
            <li><a href="index.php">Family Members</a></li>
            <li><a href="../personnels/index.php">Personnel</a></li>
            <li><a href="../locations/index.php">Locations</a></li>
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
        <div class="form-container">
            <h1>Add New Family Members</h1>

            <!-- Confirming the addition -->
            <?php if(isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="add.php" method="POST">
                <label for="family-type">Family Member Type *:</label>
                <select name="family-type" id="family-type" required>
                    <option value="primary" <?= isset($_POST['family-type']) && $_POST['family-type'] === 'primary' ? 'selected' : '' ?>>Primary</option>
                    <option value="secondary" <?= isset($_POST['family-type']) && $_POST['family-type'] === 'secondary' ? 'selected' : '' ?>>Secondary</option>
                </select>
                <br>
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
                <select name="province" id="province" required>
                    <option value="AB" <?= isset($_POST['province']) && $_POST['province'] === 'AB' ? 'selected' : '' ?>>Alberta (AB)</option>
                    <option value="NL" <?= isset($_POST['province']) && $_POST['province'] === 'NL' ? 'selected' : '' ?>>Newfoundland and Labrador (NL)</option>
                    <option value="NS" <?= isset($_POST['province']) && $_POST['province'] === 'NS' ? 'selected' : '' ?>>Nova Scotia (NS)</option>
                    <option value="PE" <?= isset($_POST['province']) && $_POST['province'] === 'PE' ? 'selected' : '' ?>>Prince Edward Island (PE)</option>
                    <option value="BC" <?= isset($_POST['province']) && $_POST['province'] === 'BC' ? 'selected' : '' ?>>British Columbia (BC)</option>
                    <option value="QC" <?= isset($_POST['province']) && $_POST['province'] === 'QC' ? 'selected' : '' ?>>Quebec (QC)</option>
                    <option value="ON" <?= isset($_POST['province']) && $_POST['province'] === 'ON' ? 'selected' : '' ?>>Ontario (ON)</option>
                    <option value="MB" <?= isset($_POST['province']) && $_POST['province'] === 'MB' ? 'selected' : '' ?>>Manitoba (MB)</option>
                    <option value="SK" <?= isset($_POST['province']) && $_POST['province'] === 'SK' ? 'selected' : '' ?>>Saskatchewan (SK)</option>
                    <option value="YT" <?= isset($_POST['province']) && $_POST['province'] === 'YT' ? 'selected' : '' ?>>Yukon (YT)</option>
                    <option value="NT" <?= isset($_POST['province']) && $_POST['province'] === 'NT' ? 'selected' : '' ?>>Northwest Territories (NT)</option>
                    <option value="NU" <?= isset($_POST['province']) && $_POST['province'] === 'NU' ? 'selected' : '' ?>>Nunavut (NU)</option>
                </select>
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