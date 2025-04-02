<?php
    $page_title = "Edit Family Members";
    require_once '../database.php';

    //Get the ID of selected family member
    $personID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    //Get the recorded data associated with the ID
    $query = "
        SELECT 
            p.*,
            fm.Email
        FROM
            Person p
        JOIN 
            FamilyMember fm ON p.PersonID = fm.PersonID
        WHERE
            p.PersonID = ?
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $personID);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $familyMember = mysqli_fetch_assoc($result);

    if (!$familyMember) {
        die("Family member not found");
    }

    //Update the data that's been change
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
            //Update the data stored
            $updatePersonQuery = "
                UPDATE Person SET
                    SSN = ?,
                    FirstName = ?,
                    LastName = ?,
                    MedicareNumber = ?,
                    DateOfBirth = ?,
                    PhoneNumber = ?,
                    Address = ?,
                    City = ?,
                    Province = ?,
                    PostalCode = ?
                WHERE 
                    PersonID = ?
            ";
            $stmt = mysqli_prepare($conn, $updatePersonQuery);
            mysqli_stmt_bind_param($stmt, "ssssssssssi", $sin, $firstName, $lastName, $medicare, $dob, $phone, $address, $city, $province, $postalCode, $personID);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to edit Person: " . mysqli_error($conn));
            }


            $updateFamilyQuery = "
                UPDATE FamilyMember SET
                    Email = ?
                WHERE
                    PersonID = ?
            ";
            $stmt = mysqli_prepare($conn, $updateFamilyQuery);
            mysqli_stmt_bind_param($stmt, "si", $email, $personID);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to edit FamilyMember: " . mysqli_error($conn));
            }

            mysqli_commit($conn);

            //Redirect to success page
            header("Location: index.php?success=1");
            exit();

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
            <h1>Editing Information for <?= htmlspecialchars($familyMember['FirstName']) ?> <?= htmlspecialchars($familyMember['LastName']) ?></h1>
            
            <?php if (isset($error)): ?>
                <div class="error">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="edit.php?id=<?= $familyMember['PersonID'] ?>" method="POST">
            <label for="first-name">First Name *:</label>
                <input type="text" name="first-name" id="first-name" required
                    value="<?= htmlspecialchars($familyMember['FirstName']) ?>">
                <br>
                
                <label for="last-name">Last Name *:</label>
                <input type="text" name="last-name" id="last-name" required
                    value="<?= htmlspecialchars($familyMember['LastName']) ?>">
                <br>
                
                <label for="email">Email Address *:</label>
                <input type="email" name="email" id="email" required
                    value="<?= htmlspecialchars($familyMember['Email']) ?>">
                <br>
                
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob"
                    value="<?= htmlspecialchars($familyMember['DateOfBirth']) ?>">
                <br>
                
                <label for="sin">SIN:</label>
                <input type="text" name="sin" id="sin"
                    value="<?= htmlspecialchars($familyMember['SSN']) ?>">
                <br>
                
                <label for="medicare-card">Medicare Card Number:</label>
                <input type="text" name="medicare-card" id="medicare-card"
                    value="<?= htmlspecialchars($familyMember['MedicareNumber']) ?>">
                <br>
                
                <label for="telephone-number">Telephone Number *:</label>
                <input type="text" name="telephone-number" id="telephone-number" required
                    value="<?= htmlspecialchars($familyMember['PhoneNumber']) ?>">
                <br>
                
                <label for="address">Address *:</label>
                <input type="text" name="address" id="address" required
                    value="<?= htmlspecialchars($familyMember['Address']) ?>">
                <br>
                
                <label for="city">City *:</label>
                <input type="text" name="city" id="city" required
                    value="<?= htmlspecialchars($familyMember['City']) ?>">
                <br>
                
                <label for="province">Province *:</label>
                <input type="text" name="province" id="province" required
                    value="<?= htmlspecialchars($familyMember['Province']) ?>">
                <br>
                
                <label for="postal-code">Postal Code *:</label>
                <input type="text" name="postal-code" id="postal-code" required
                    value="<?= htmlspecialchars($familyMember['PostalCode']) ?>">
                <br>
                
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Update Family Member</button>
                <button class="cancel-btn" onclick="window.location.href='index.php'">Cancel</button>
            </form>
        </div>
    </main>
</body>