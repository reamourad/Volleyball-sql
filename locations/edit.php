<?php
    $page_title = "Edit Location";
    require_once '../database.php';

    //Get the locationID
    $locationID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    //Check if location exist
    $query = "
        SELECT *
        FROM Location
        WHERE LocationID = ?
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $locationID);
    mysqli_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $location = mysqli_fetch_assoc($result);

    if(!$location){
        die("Location not found");
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //Get values
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $province = mysqli_real_escape_string($conn, $_POST['province']);
        $postalCode = mysqli_real_escape_string($conn, $_POST['postal-code']);
        $phone = mysqli_real_escape_string($conn, $_POST['telephone-number']);
        $website = mysqli_real_escape_string($conn, $_POST['website']);
        $capacity = mysqli_real_escape_string($conn, $_POST['capacity']);

        mysqli_begin_transaction($conn);

        try {
            //Insert into Person
            $locationQuery = "
                UPDATE Location SET
                    Type = ?, 
                    Name = ?, 
                    Address = ?, 
                    City = ?, 
                    Province = ?, 
                    PostalCode = ?, 
                    PhoneNumber = ?, 
                    Website = ?, 
                    Capacity = ?
                WHERE 
                    LocationID = ?
            ";

            $stmt = mysqli_prepare($conn, $locationQuery);
            mysqli_stmt_bind_param($stmt, 'ssssssssii', $type, $name, $address, $city, $province, $postalCode, $phone, $website, $capacity, $locationID);
        
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to update Location: " . mysqli_error($conn));
            }

            mysqli_commit($conn);

            // Redirect with success parameter
            header("Location: index.php?success=1");
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
            <li><a href="index.php">Locations</a></li>
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
            <h1>Add New Location</h1>

            <!-- Confirming the addition -->
            <?php if(isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="edit.php?id=<?= $location['LocationID'] ?>" method="POST">
                <label for="type">Type *:</label>
                <select name="type" id="type" required>
                    <option value="Head" <?= $location['Type'] === 'Head' ? 'selected' : '' ?>>Head</option>
                    <option value="Branch" <?= $location['Type'] === 'Branch' ? 'selected' : '' ?>>Branch</option>
                </select>
                <br>
                
                <label for="name">Name *:</label>
                <input type="text" name="name" id="name" required
                    value="<?= htmlspecialchars($location['Name']) ?>">
                <br>
                
                <label for="address">Address *:</label>
                <input type="text" name="address" id="address" required
                    value="<?= htmlspecialchars($location['Address']) ?>">
                <br>
                
                <label for="city">City *:</label>
                <input type="text" name="city" id="city" required
                    value="<?= htmlspecialchars($location['City']) ?>">
                <br>
                
                <label for="province">Province *:</label>
                <select name="province" id="province" required>
                    <option value="AB" <?= $location['Province'] === 'AB' ? 'selected' : '' ?>>Alberta (AB)</option>
                    <option value="NL" <?= $location['Province'] === 'NL' ? 'selected' : '' ?>>Newfoundland and Labrador (NL)</option>
                    <option value="NS" <?= $location['Province'] === 'NS' ? 'selected' : '' ?>>Nova Scotia (NS)</option>
                    <option value="PE" <?= $location['Province'] === 'PE' ? 'selected' : '' ?>>Prince Edward Island (PE)</option>
                    <option value="BC" <?= $location['Province'] === 'BC' ? 'selected' : '' ?>>British Columbia (BC)</option>
                    <option value="QC" <?= $location['Province'] === 'QC' ? 'selected' : '' ?>>Quebec (QC)</option>
                    <option value="ON" <?= $location['Province'] === 'ON' ? 'selected' : '' ?>>Ontario (ON)</option>
                    <option value="MB" <?= $location['Province'] === 'MB' ? 'selected' : '' ?>>Manitoba (MB)</option>
                    <option value="SK" <?= $location['Province'] === 'SK' ? 'selected' : '' ?>>Saskatchewan (SK)</option>
                    <option value="YT" <?= $location['Province'] === 'YT' ? 'selected' : '' ?>>Yukon (YT)</option>
                    <option value="NT" <?= $location['Province'] === 'NT' ? 'selected' : '' ?>>Northwest Territories (NT)</option>
                    <option value="NU" <?= $location['Province'] === 'NU' ? 'selected' : '' ?>>Nunavut (NU)</option>
                </select>
                <br>
                
                <label for="postal-code">Postal Code *:</label>
                <input type="text" name="postal-code" id="postal-code" required
                    value="<?= htmlspecialchars($location['PostalCode']) ?>">
                <br>
                
                <label for="telephone-number">Phone Number *:</label>
                <input type="text" name="telephone-number" id="telephone-number" required
                    value="<?= htmlspecialchars($location['PhoneNumber']) ?>">
                <br>
                
                <label for="website">Website *:</label>
                <input type="text" name="website" id="website" required
                    value="<?= htmlspecialchars($location['Website']) ?>">
                <br>
                
                <label for="capacity">Capacity *:</label>
                <input type="number" name="capacity" id="capacity" required
                    value="<?= htmlspecialchars($location['Capacity']) ?>">
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Update Location</button>
                <button class="cancel-btn" onclick="window.location.href='index.php'">Cancel</button>
            </form>
        </div>
    </main>
</body>