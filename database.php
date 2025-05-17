<?php
    //Get the database connection details
    use Dotenv\Dotenv;
    require_once __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    //Connect to the database
    $servername = $_ENV['SERVERNAME'];
    $username = $_ENV['USERNAME'];
    $password = $_ENV['PASSWORD'];
    $dbname = $_ENV['NAME'];

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
?>