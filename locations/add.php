<?php
    $page_title = "Add Location";
    require_once '../database.php';

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
                INSERT INTO Location (Type, Name, Address, City, Province, PostalCode, PhoneNumber, Website, Capacity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";

            $stmt = mysqli_prepare($conn, $locationQuery);
            mysqli_stmt_bind_param($stmt, 'ssssssssi', $type, $name, $address, $city, $province, $postalCode, $phone, $website, $capacity);
        
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to add Location: " . mysqli_error($conn));
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
            <h1>Add New Personnel</h1>

            <!-- Confirming the addition -->
            <?php if(isset($error)): ?>
                <div class="error" style="color: red; font-weight: bold; margin-top: 20px;">Error: <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="add.php" method="POST">
                <label for="type">Type *:</label>
                <select name="type" id="type" required>
                    <option value="Head" <?= isset($_POST['type']) && $_POST['type'] === 'Head' ? 'selected' : '' ?>>Head</option>
                    <option value="Branch" <?= isset($_POST['type']) && $_POST['type'] === 'Branch' ? 'selected' : '' ?>>Branch</option>
                </select>
                <br>
                <label for="name">Name *:</label>
                <input type="text" name="name" id="name" required
                    value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>"
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
                <label for="telephone-number">Phone Number *:</label>
                <input type="text" name="telephone-number" id="telephone-number" required
                    value="<?= isset($_POST['telephone-number']) ? htmlspecialchars($_POST['telephone-number']) : '' ?>"
                >
                <br>
                <label for="website">Website *:</label>
                <input type="text" name="website" id="website" required
                    value="<?= isset($_POST['website']) ? htmlspecialchars($_POST['website']) : '' ?>"
                >
                <br>
                <label for="capacity">Capacity *:</label>
                <input type="number" name="capacity" id="capacity" required
                    value="<?= isset($_POST['capacity']) ? htmlspecialchars($_POST['capacity']) : '' ?>"
                >
                <p>* This indicates that the field must be filled</p>
                <button type="submit">Add Location</button>
            </form>
        </div>
    </main>
</body>