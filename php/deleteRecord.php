<!doctype html>
<html>

<head>
    <title>Delete a Record</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>

<?php
$servername = getenv('DB_HOST');
$username   = getenv('DB_USER');
$password   = getenv('DB_PASSWORD');
$dbname     = getenv('DB_NAME');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>Database connection successful</p>";
} catch (PDOException $err) {
    die("<p style='color:red'>Connection failed: " . $err->getMessage() . "</p>");
}

// Get table and keys from POST
$table = $_POST['table'] ?? '';
$keys  = $_POST['keys'] ?? [];  // associative array: ['PrimaryKeyCol'=>'value', ...]

if (!$table || empty($keys)) {
    echo "<p style='color:red'>Table or keys not provided</p>";
    exit;
}

// Build WHERE clause dynamically
$whereClauses = [];
foreach ($keys as $col => $val) {
    $whereClauses[] = "$col = :$col";
}
$where = implode(" AND ", $whereClauses);

try {
    // Check if record exists
    $sql = "SELECT * FROM $table WHERE $where";
    $stmt = $conn->prepare($sql);
    foreach ($keys as $col => $val) {
        $stmt->bindValue(":$col", $val);
    }
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        echo "<p style='color:red'>Record not found. Cannot delete.</p>";
    } else {
        // Delete record
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $conn->prepare($sql);
        foreach ($keys as $col => $val) {
            $stmt->bindValue(":$col", $val);
        }
        $stmt->execute();
        echo "<p style='color:green'>Record deleted successfully from $table</p>";
    }
} catch (PDOException $err) {
    echo "<p style='color:red'>Error: " . $err->getMessage() . "</p>";
}

unset($conn);

echo "<a href='../index.html'>Back to Homepage</a>";
?>

</body>
</html>
