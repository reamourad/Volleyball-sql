<?php
    $page_title = "Edit Personnel";
    require_once '../database.php';

    //Get the ID of selected personnel
    $personID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    //Get the recorded data associated with the ID
    $query = "
        SELECT 
            p.*,
            pers.Mandate
        FROM
            Person p
        JOIN 
            Personnel pers ON p.PersonID = pers.EmployeeID
        WHERE
            p.PersonID = ?
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $personID);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $personnel = mysqli_fetch_assoc($result);

    if (!$personnel) {
        die("Personnel not found");
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
        $mandate = mysqli_real_escape_string($conn, $_POST['mandate']);

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
                    Email =  ?,
                    PhoneNumber = ?,
                    Address = ?,
                    City = ?,
                    Province = ?,
                    PostalCode = ?
                WHERE 
                    PersonID = ?
            ";
            $stmt = mysqli_prepare($conn, $updatePersonQuery);
            mysqli_stmt_bind_param($stmt, "sssssssssssi", $sin, $firstName, $lastName, $medicare, $dob, $email, $phone, $address, $city, $province, $postalCode, $personID);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to edit Person: " . mysqli_error($conn));
            }


            $updatePersonnelQuery = "
                UPDATE Personnel SET
                    Mandate  = ?
                WHERE
                    EmployeeID = ?
            ";
            $stmt = mysqli_prepare($conn, $updatePersonnelQuery);
            mysqli_stmt_bind_param($stmt, "si", $mandate, $personID);

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
            <li><a href="../familyMembers/index.php">Family Members</a></li>
            <li><a href="../personnels/index.php">Personnel</a></li>
            <li><a href="../locations/index.php">Locations</a></li>
            <li><a href="../teamFormations/index.php">Team Formation</a></li>
            <li><a href="../emailLog/index.php">Email Logs</a></li>

            <!-- Reports Dropdown -->
            <li class="dropdown">
                <a href="#">Queries</a>
                <ul class="dropdown-content">
                    <li><a href="../queries/query9.php">Query 9</a></li>
                    <li><a href="../queries/query10.php">Query 10</a></li>
                    <li><a href="../queries/query11.php">Query 11</a></li>
                    <li><a href="../queries/query12.php">Query 12</a></li>
                    <li><a href="../queries/query13.php">Query 13</a></li>
                    <li><a href="../queries/query14.php">Query 14</a></li>
                    <li><a href="../queries/query15.php">Query 15</a></li>
                    <li><a href="../queries/query16.php">Query 16</a></li>
                    <li><a href="../queries/query17.php">Query 17</a></li>
                    <li><a href="../queries/query18.php">Query 18</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Main Section -->
    <main>
        <div class="form-container">
            <h1>Editing Information for <?= htmlspecialchars($personnel['FirstName']) ?> <?= htmlspecialchars($personnel['LastName']) ?></h1>
            
            <?php if (isset($error)): ?>
                <div class="error">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="edit.php?id=<?= $personnel['PersonID'] ?>" method="POST">
                <label for="first-name">First Name *:</label>
                <input type="text" name="first-name" id="first-name" required
                    value="<?= htmlspecialchars($personnel['FirstName']) ?>">
                <br>
                
                <label for="last-name">Last Name *:</label>
                <input type="text" name="last-name" id="last-name" required
                    value="<?= htmlspecialchars($personnel['LastName']) ?>">
                <br>
                
                <label for="email">Email Address *:</label>
                <input type="email" name="email" id="email" required
                    value="<?= htmlspecialchars($personnel['Email']) ?>">
                <br>
                
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob"
                    value="<?= htmlspecialchars($personnel['DateOfBirth']) ?>">
                <br>
                
                <label for="sin">SIN:</label>
                <input type="text" name="sin" id="sin"
                    value="<?= htmlspecialchars($personnel['SSN']) ?>">
                <br>
                
                <label for="medicare-card">Medicare Card Number:</label>
                <input type="text" name="medicare-card" id="medicare-card"
                    value="<?= htmlspecialchars($personnel['MedicareNumber']) ?>">
                <br>
                
                <label for="telephone-number">Telephone Number *:</label>
                <input type="text" name="telephone-number" id="telephone-number" required
                    value="<?= htmlspecialchars($personnel['PhoneNumber']) ?>">
                <br>
                
                <label for="address">Address *:</label>
                <input type="text" name="address" id="address" required
                    value="<?= htmlspecialchars($personnel['Address']) ?>">
                <br>
                
                <label for="city">City *:</label>
                <input type="text" name="city" id="city" required
                    value="<?= htmlspecialchars($personnel['City']) ?>">
                <br>
                
                <label for="province">Province *:</label>
                <select name="province" id="province" required>
                    <option value="AB" <?= $personnel['Province'] === 'AB' ? 'selected' : '' ?>>Alberta (AB)</option>
                    <option value="NL" <?= $personnel['Province'] === 'NL' ? 'selected' : '' ?>>Newfoundland and Labrador (NL)</option>
                    <option value="NS" <?= $personnel['Province'] === 'NS' ? 'selected' : '' ?>>Nova Scotia (NS)</option>
                    <option value="PE" <?= $personnel['Province'] === 'PE' ? 'selected' : '' ?>>Prince Edward Island (PE)</option>
                    <option value="BC" <?= $personnel['Province'] === 'BC' ? 'selected' : '' ?>>British Columbia (BC)</option>
                    <option value="QC" <?= $personnel['Province'] === 'QC' ? 'selected' : '' ?>>Quebec (QC)</option>
                    <option value="ON" <?= $personnel['Province'] === 'ON' ? 'selected' : '' ?>>Ontario (ON)</option>
                    <option value="MB" <?= $personnel['Province'] === 'MB' ? 'selected' : '' ?>>Manitoba (MB)</option>
                    <option value="SK" <?= $personnel['Province'] === 'SK' ? 'selected' : '' ?>>Saskatchewan (SK)</option>
                    <option value="YT" <?= $personnel['Province'] === 'YT' ? 'selected' : '' ?>>Yukon (YT)</option>
                    <option value="NT" <?= $personnel['Province'] === 'NT' ? 'selected' : '' ?>>Northwest Territories (NT)</option>
                    <option value="NU" <?= $personnel['Province'] === 'NU' ? 'selected' : '' ?>>Nunavut (NU)</option>
                </select>
                <br>
                
                <label for="postal-code">Postal Code *:</label>
                <input type="text" name="postal-code" id="postal-code" required
                    value="<?= htmlspecialchars($personnel['PostalCode']) ?>">
                <br>
                <label for="mandate">Mandate *:</label>
                <select name="mandate" id="mandate">
                    <option value="Salaried" <?= $personnel['Mandate']  === 'Salaried' ? 'selected' : '' ?>>Salaried</option>
                    <option value="Volunteer" <?= $personnel['Mandate'] === 'Volunteer' ? 'selected' : '' ?>>Volunteer</option>
                </select>
                
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Update Personnel</button>
                <button class="cancel-btn" onclick="window.location.href='index.php'">Cancel</button>
            </form>
        </div>
    </main>
</body>