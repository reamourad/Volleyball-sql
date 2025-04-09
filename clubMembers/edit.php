<?php
    $page_title = "Edit Club Members";
    require_once '../database.php';

     //Get the ID of selected family member
    $personID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    //Get the recorded data associated with the ID
    $query = "
        SELECT 
            p.*,
            cm.Gender, 
            cm.Height, 
            cm.Weight, 
            cm.LocationID, 
            cm.PrimaryFamilyID, 
            cm.Relationship, 
            cm.AlternativeFamilyID, 
            cm.AlternativeRelationship
        FROM
            Person p
        JOIN 
            ClubMember cm ON p.PersonID = cm.PersonID
        WHERE
            p.PersonID = ?
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $personID);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $clubMember = mysqli_fetch_assoc($result);

    if (!$clubMember) {
        die("Club member not found");
    }

    //Update the data
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
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $height = !empty($_POST['height']) ? (float)$_POST['height'] : null;
        $weight = !empty($_POST['weight']) ? (float)$_POST['weight'] : null;
        $primaryFamilyID = (int)$_POST['primary-family-member-id'];
        $primaryRelationship = mysqli_real_escape_string($conn, $_POST['primary-relationship']);
        $alternativeFamilyID = !empty($_POST['alternative-family-member-id']) ? (int)$_POST['alternative-family-member-id'] : null;
        $alternativeRelationship = !empty($_POST['alternative-relationship']) ? mysqli_real_escape_string($conn, $_POST['alternative-relationship']) : null;
        $locationID = (int)$_POST['location-id'];

        mysqli_begin_transaction($conn);

        try{
            //Update the data stored
            $updatePersonQuery = "
                UPDATE Person SET
                    SSN = ?,
                    FirstName = ?,
                    LastName = ?,
                    MedicareNumber = ?,
                    DateOfBirth = ?,
                    Email = ?,
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

            $updateClubMemberQuery = "
                UPDATE ClubMember SET
                    Gender = ?,
                    Height = ?,
                    Weight = ?,
                    LocationID = ?,
                    PrimaryFamilyID = ?,
                    Relationship = ?,
                    AlternativeFamilyID = ?,
                    AlternativeRelationship = ?
                WHERE 
                    PersonID = ?
            ";
            $stmt = mysqli_prepare($conn, $updateClubMemberQuery);
            mysqli_stmt_bind_param($stmt, 'sddissisi', $gender, $height, $weight, $locationID, $primaryFamilyID, $primaryRelationship, $alternativeFamilyID, $alternativeRelationship, $personID);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to edit ClubMember: " . mysqli_error($conn));
            }

            mysqli_commit($conn);

            // Redirect with success parameter
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
            <h1>Editing Information for <?= htmlspecialchars($clubMember['FirstName']) ?> <?= htmlspecialchars($clubMember['LastName']) ?></h1>

            <?php if(isset($error)):?>
                <div class="error">Error: <? htmlspecialchars($error)?></div>
            <?php endif; ?>

            <form action="edit.php?id=<?= htmlspecialchars($personID) ?>" method="POST">
                <label for="first-name">First Name *:</label>
                <input type="text" name="first-name" id="first-name" required
                    value="<?= htmlspecialchars($clubMember['FirstName']) ?>">
                <br>

                <label for="last-name">Last Name *:</label>
                <input type="text" name="last-name" id="last-name" required
                    value="<?= htmlspecialchars($clubMember['LastName']) ?>">
                <br>

                <label for="dob">Date of Birth *:</label>
                <input type="date" name="dob" id="dob" required
                    value="<?= htmlspecialchars($clubMember['DateOfBirth']) ?>">
                <br>

                <label for="sin">SIN:</label>
                <input type="text" name="sin" id="sin"
                    value="<?= htmlspecialchars($clubMember['SSN']) ?>">
                <br>

                <label for="medicare-card">Medicare Card Number:</label>
                <input type="text" name="medicare-card" id="medicare-card"
                    value="<?= htmlspecialchars($clubMember['MedicareNumber']) ?>">
                <br>

                <label for="telephone-number">Telephone Number *:</label>
                <input type="text" name="telephone-number" id="telephone-number" required
                    value="<?= htmlspecialchars($clubMember['PhoneNumber']) ?>">
                <br>

                <label for="address">Address *:</label>
                <input type="text" name="address" id="address" required
                    value="<?= htmlspecialchars($clubMember['Address']) ?>">
                <br>

                <label for="city">City *:</label>
                <input type="text" name="city" id="city" required
                    value="<?= htmlspecialchars($clubMember['City']) ?>">
                <br>

                <label for="province">Province *:</label>
                <select name="province" id="province" required>
                    <option value="AB" <?= $clubMember['Province'] === 'AB' ? 'selected' : '' ?>>Alberta (AB)</option>
                    <option value="NL" <?= $clubMember['Province'] === 'NL' ? 'selected' : '' ?>>Newfoundland and Labrador (NL)</option>
                    <option value="NS" <?= $clubMember['Province'] === 'NS' ? 'selected' : '' ?>>Nova Scotia (NS)</option>
                    <option value="PE" <?= $clubMember['Province'] === 'PE' ? 'selected' : '' ?>>Prince Edward Island (PE)</option>
                    <option value="BC" <?= $clubMember['Province'] === 'BC' ? 'selected' : '' ?>>British Columbia (BC)</option>
                    <option value="QC" <?= $clubMember['Province'] === 'QC' ? 'selected' : '' ?>>Quebec (QC)</option>
                    <option value="ON" <?= $clubMember['Province'] === 'ON' ? 'selected' : '' ?>>Ontario (ON)</option>
                    <option value="MB" <?= $clubMember['Province'] === 'MB' ? 'selected' : '' ?>>Manitoba (MB)</option>
                    <option value="SK" <?= $clubMember['Province'] === 'SK' ? 'selected' : '' ?>>Saskatchewan (SK)</option>
                    <option value="YT" <?= $clubMember['Province'] === 'YT' ? 'selected' : '' ?>>Yukon (YT)</option>
                    <option value="NT" <?= $clubMember['Province'] === 'NT' ? 'selected' : '' ?>>Northwest Territories (NT)</option>
                    <option value="NU" <?= $clubMember['Province'] === 'NU' ? 'selected' : '' ?>>Nunavut (NU)</option>
                </select>

                <label for="postal-code">Postal Code *:</label>
                <input type="text" name="postal-code" id="postal-code" required
                    value="<?= htmlspecialchars($clubMember['PostalCode']) ?>">
                <br>

                <label for="gender">Gender (M/F) *:</label>
                <select name="gender" id="gender" required>
                    <option value="M" <?= $clubMember['Gender'] === 'M' ? 'selected' : '' ?>>Male</option>
                    <option value="F" <?= $clubMember['Gender'] === 'F' ? 'selected' : '' ?>>Female</option>
                </select>
                <br>

                <label for="height">Height (in cm):</label>
                <input type="number" step="0.01" name="height" id="height"
                    value="<?= htmlspecialchars($clubMember['Height']) ?>">
                <br>

                <label for="weight">Weight (in kg):</label>
                <input type="number" step="0.01" name="weight" id="weight"
                    value="<?= htmlspecialchars($clubMember['Weight']) ?>">
                <br>

                <label for="primary-family-member-id">Primary Family Member ID *:</label>
                <input type="text" name="primary-family-member-id" id="primary-family-member-id" required pattern="\d+"
                    value="<?= htmlspecialchars($clubMember['PrimaryFamilyID']) ?>">
                <br>

                <label for="primary-relationship">Relationship with Primary Family Member *:</label>
                <select name="primary-relationship" id="primary-relationship" required>
                    <option value="Father" <?= $clubMember['Relationship'] === 'Father' ? 'selected' : '' ?>>Father</option>
                    <option value="Mother" <?= $clubMember['Relationship'] === 'Mother' ? 'selected' : '' ?>>Mother</option>
                    <option value="Grandfather" <?= $clubMember['Relationship'] === 'Grandfather' ? 'selected' : '' ?>>Grandfather</option>
                    <option value="Grandmother" <?= $clubMember['Relationship'] === 'Grandmother' ? 'selected' : '' ?>>Grandmother</option>
                    <option value="Uncle" <?= $clubMember['Relationship'] === 'Uncle' ? 'selected' : '' ?>>Uncle</option>
                    <option value="Aunt" <?= $clubMember['Relationship'] === 'Aunt' ? 'selected' : '' ?>>Aunt</option>
                    <option value="Tutor" <?= $clubMember['Relationship'] === 'Tutor' ? 'selected' : '' ?>>Tutor</option>
                    <option value="Partner" <?= $clubMember['Relationship'] === 'Partner' ? 'selected' : '' ?>>Partner</option>
                    <option value="Friend" <?= $clubMember['Relationship'] === 'Friend' ? 'selected' : '' ?>>Friend</option>
                    <option value="Son" <?= $clubMember['Relationship'] === 'Son' ? 'selected' : '' ?>>Son</option>
                    <option value="Daughter" <?= $clubMember['Relationship'] === 'Daughter' ? 'selected' : '' ?>>Daughter</option>
                    <option value="Other" <?= $clubMember['Relationship'] === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
                <br>

                <label for="alternative-family-member-id">Alternative Family Member ID:</label>
                <input type="text" name="alternative-family-member-id" id="alternative-family-member-id" pattern="\d*"
                    value="<?= htmlspecialchars($clubMember['AlternativeFamilyID']) ?>">
                <br>

                <label for="alternative-relationship">Relationship with Alternative Family Member:</label>
                <select name="alternative-relationship" id="alternative-relationship">
                    <option value="" <?= empty($clubMember['AlternativeRelationship']) ? 'selected' : '' ?>>-- None --</option>
                    <option value="Father" <?= $clubMember['AlternativeRelationship'] === 'Father' ? 'selected' : '' ?>>Father</option>
                    <option value="Mother" <?= $clubMember['AlternativeRelationship'] === 'Mother' ? 'selected' : '' ?>>Mother</option>
                    <option value="Grandfather" <?= $clubMember['AlternativeRelationship'] === 'Grandfather' ? 'selected' : '' ?>>Grandfather</option>
                    <option value="Grandmother" <?= $clubMember['AlternativeRelationship'] === 'Grandmother' ? 'selected' : '' ?>>Grandmother</option>
                    <option value="Uncle" <?= $clubMember['AlternativeRelationship'] === 'Uncle' ? 'selected' : '' ?>>Uncle</option>
                    <option value="Aunt" <?= $clubMember['AlternativeRelationship'] === 'Aunt' ? 'selected' : '' ?>>Aunt</option>
                    <option value="Tutor" <?= $clubMember['AlternativeRelationship'] === 'Tutor' ? 'selected' : '' ?>>Tutor</option>
                    <option value="Partner" <?= $clubMember['AlternativeRelationship'] === 'Partner' ? 'selected' : '' ?>>Partner</option>
                    <option value="Friend" <?= $clubMember['AlternativeRelationship'] === 'Friend' ? 'selected' : '' ?>>Friend</option>
                    <option value="Son" <?= $clubMember['AlternativeRelationship'] === 'Son' ? 'selected' : '' ?>>Son</option>
                    <option value="Daughter" <?= $clubMember['AlternativeRelationship'] === 'Daughter' ? 'selected' : '' ?>>Daughter</option>
                    <option value="Other" <?= $clubMember['AlternativeRelationship'] === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
                <br>

                <label for="location-id">Location ID *:</label>
                <input type="text" name="location-id" id="location-id" required pattern="\d+"
                    value="<?= htmlspecialchars($clubMember['LocationID']) ?>">
                <br>

                <p>* This indicates that the field must be filled</p>
                <button type="submit">Update Club Member</button>
                <button class="cancel-btn" onclick="window.location.href='index.php'">Cancel</button>
            </form>
        </div>
    </main>
</body>

