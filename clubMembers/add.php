<?php
    $page_title = "Add Club Members";
    require_once '../database.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Get values
        $firstName = mysqli_real_escape_string($conn, $_POST['first-name']);
        $lastName = mysqli_real_escape_string($conn, $_POST['last-name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
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
            //Insert into Person
            $personQuery = "
                INSERT INTO Person (SSN, FirstName, LastName, MedicareNumber, DateOfBirth, Email, PhoneNumber, Address, City, Province, PostalCode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmt = mysqli_prepare($conn, $personQuery);
            mysqli_stmt_bind_param($stmt, 'issssssssss', $sin, $firstName, $lastName, $medicare, $dob, $email, $phone, $address, $city, $province, $postalCode);
        
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to add Person: " . mysqli_error($conn));
            }

            //Insert into ClubMember
            $personID = mysqli_insert_id($conn);
            $clubMemberQuery = "
                INSERT INTO ClubMember (Gender, Height, Weight, LocationID, PrimaryFamilyID, Relationship, AlternativeFamilyID, AlternativeRelationship, PersonID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
            $stmt = mysqli_prepare($conn, $clubMemberQuery);
            mysqli_stmt_bind_param($stmt, 'sddissisi', $gender, $height, $weight, $locationID, $primaryFamilyID, $primaryRelationship, $alternativeFamilyID, $alternativeRelationship, $personID);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to add ClubMember: " . mysqli_error($conn));
            }

            // Commit the transaction
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
            <li><a href="../sessions/index.php">Sessions</a></li>
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
            <h1>Add New Club Members</h1>

            <!-- Confirming the addition -->
            <?php if(isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
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
                <label for="dob">Date of Birth *:</label>
                <input type="date" name="dob" id="dob" require
                    value="<?= isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : '' ?>"
                >
                <br>
                <label for="sin">SIN *:</label>
                <input type="text" name="sin" id="sin"
                    value="<?= isset($_POST['sin']) ? htmlspecialchars($_POST['sin']) : '' ?>"
                >
                <br>
                <label for="medicare-card">Medicare Card Number *:</label>
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
                <label for="gender">Gender (M/F) *:</label>
                <select name="gender" id="gender" required>
                    <option value="M" <?= isset($_POST['gender']) && $_POST['gender'] === 'M' ? 'selected' : '' ?>>Male</option>
                    <option value="F" <?= isset($_POST['gender']) && $_POST['gender'] === 'F' ? 'selected' : '' ?>>Female</option>
                </select>
                <br>
                <label for="height">Height (in cm) *:</label>
                <input type="number" step="0.01" name="height" id="height"
                    value="<?= isset($_POST['height']) ? htmlspecialchars($_POST['height']) : '' ?>"
                >
                <br>
                <label for="weight">Weight (in kg) *:</label>
                <input type="number" step="0.01" name="weight" id="weight"
                    value="<?= isset($_POST['weight']) ? htmlspecialchars($_POST['weight']) : '' ?>"
                >
                <br>
                <label for="primary-family-member-id">Primary Family Member ID *:</label>
                <input type="text" name="primary-family-member-id" id="primary-family-member-id" required pattern="\d+"
                    value="<?= isset($_POST['primary-family-member-id']) ? htmlspecialchars($_POST['primary-family-member-id']) : '' ?>"
                >
                <br>
                <label for="primary-relationship">Relationship with Primary Family Member *:</label>
                <select name="primary-relationship" id="primary-relationship" required>
                    <option value="Father" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Father' ? 'selected' : '' ?>>Father</option>
                    <option value="Mother" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Mother' ? 'selected' : '' ?>>Mother</option>
                    <option value="Grandfather" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Grandfather' ? 'selected' : '' ?>>Grandfather</option>
                    <option value="Grandmother" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Grandmother' ? 'selected' : '' ?>>Grandmother</option>
                    <option value="Uncle" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Uncle' ? 'selected' : '' ?>>Uncle</option>
                    <option value="Aunt" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Aunt' ? 'selected' : '' ?>>Aunt</option>
                    <option value="Tutor" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Tutor' ? 'selected' : '' ?>>Tutor</option>
                    <option value="Partner" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Partner' ? 'selected' : '' ?>>Partner</option>
                    <option value="Friend" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Friend' ? 'selected' : '' ?>>Friend</option>
                    <option value="Other" <?= isset($_POST['primary-relationship']) && $_POST['primary-relationship'] === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
                <br>
                <label for="alternative-family-member-id">Alternative Family Member ID:</label>
                <input type="text" name="alternative-family-member-id" id="alternative-family-member-id" pattern="\d*"
                    value="<?= isset($_POST['alternative-family-member-id']) ? htmlspecialchars($_POST['alternative-family-member-id']) : '' ?>"
                >
                <br>
                <label for="alternative-relationship">Relationship with Alternative Family Member:</label>
                <select name="alternative-relationship" id="alternative-relationship">
                    <option value="" <?= !isset($_POST['alternative-relationship']) || $_POST['alternative-relationship'] === '' ? 'selected' : '' ?>>-- None --</option>
                    <option value="Father" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Father' ? 'selected' : '' ?>>Father</option>
                    <option value="Mother" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Mother' ? 'selected' : '' ?>>Mother</option>
                    <option value="Grandfather" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Grandfather' ? 'selected' : '' ?>>Grandfather</option>
                    <option value="Grandmother" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Grandmother' ? 'selected' : '' ?>>Grandmother</option>
                    <option value="Uncle" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Uncle' ? 'selected' : '' ?>>Uncle</option>
                    <option value="Aunt" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Aunt' ? 'selected' : '' ?>>Aunt</option>
                    <option value="Tutor" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Tutor' ? 'selected' : '' ?>>Tutor</option>
                    <option value="Partner" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Partner' ? 'selected' : '' ?>>Partner</option>
                    <option value="Friend" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Friend' ? 'selected' : '' ?>>Friend</option>
                    <option value="Other" <?= isset($_POST['alternative-relationship']) && $_POST['alternative-relationship'] === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
                <br>
                <label for="location-id">Location ID *:</label>
                <input type="text" name="location-id" id="location-id" required pattern="\d+"
                    value="<?= isset($_POST['location-id']) ? htmlspecialchars($_POST['location-id']) : '' ?>"
                >
                <br>
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Add Club Member</button>
            </form>
        </div>
    </main>
</body>