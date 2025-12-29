<!doctype html>
<html>

<head>
    <title>Connect to a Database</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>

<?php
// Get database credentials from environment variables
$servername = getenv('DB_HOST');
$username   = getenv('DB_USER');
$password   = getenv('DB_PASSWORD');
$dbname     = getenv('DB_NAME');

try {
    // Connect to MySQL database with PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p style='color:green'>Connection Was Successful!</p>";

    // Optional: list tables to ensure database is accessible
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if ($tables) {
        echo "<p>Tables in database: " . implode(", ", $tables) . "</p>";
    } else {
        echo "<p>No tables found in the database.</p>";
    }

} catch (PDOException $err) {
    echo "<p style='color:red'>Connection Failed: " . $err->getMessage() . "</p>";
}

// Close the connection
unset($conn);

?>

<a href='../index.html'>Back to the Homepage</a>

</body>
</html>
