<!doctype html>
<html>
<head>
    <title>Update a record of a table</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>

<?php
require 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>Connection Successful</p>";
} catch (PDOException $err) {
    die("<p style='color:red'>Connection Failed: " . $err->getMessage() . "</p>");
}

// Get POST data
$table = $_POST['table'] ?? '';
$primaryKey = $_POST['primaryKey'] ?? '';
$primaryValue = $_POST['primaryValue'] ?? '';
$column = $_POST['column'] ?? '';
$newValue = $_POST['newValue'] ?? '';

if (!$table || !$primaryKey || !$primaryValue || !$column || !$newValue) {
    die("<p style='color:red'>All fields are required.</p>");
}

try {
    // Check if record exists
    $stmt = $conn->prepare("SELECT * FROM $table WHERE $primaryKey = :pk");
    $stmt->bindParam(':pk', $primaryValue);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        echo "<p style='color:red'>Record not found. Cannot update.</p>";
    } else {
        // Detect column type
        $typeStmt = $conn->prepare("DESCRIBE $table $column");
        $typeStmt->execute();
        $colInfo = $typeStmt->fetch(PDO::FETCH_ASSOC);

        if (!$colInfo) {
            throw new Exception("Column not found in table.");
        }

        // If numeric type, leave as is; else, wrap in quotes
        $numericTypes = ['int','decimal','float','double','tinyint','smallint','mediumint','bigint'];
        $dataType = strtolower(preg_replace('/\(.*/','',$colInfo['Type'])); // remove size part

        if (in_array($dataType, $numericTypes)) {
            $sql = "UPDATE $table SET $column = :val WHERE $primaryKey = :pk";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':val', $newValue, PDO::PARAM_INT);
        } else {
            $sql = "UPDATE $table SET $column = :val WHERE $primaryKey = :pk";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':val', $newValue, PDO::PARAM_STR);
        }

        $stmt->bindValue(':pk', $primaryValue);
        $stmt->execute();

        echo "<p style='color:green'>Record updated successfully.</p>";
    }

} catch (PDOException $err) {
    echo "<p style='color:red'>Record update failed: " . $err->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}

unset($conn);

echo "<br><a href='../updateForm.html'>Back to Update Form</a>";
?>


</body>
</html>
