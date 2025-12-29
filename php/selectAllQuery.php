<!doctype html>
<html>
<head>
    <title>Display Records of All Tables</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
require 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>Connection Was Successful</p>";
} catch (PDOException $err) {
    die("<p style='color:red'>Connection Failed: " . $err->getMessage() . "</p>");
}

// Function to display a table
function displayTable($conn, $tableName, $columns) {
    try {
        $colStr = implode(',', $columns);
        $sql = "SELECT $colStr FROM $tableName";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "<h2>$tableName</h2>";

        if ($row) {
            echo '<table border="1" cellspacing="0" cellpadding="5">';
            echo '<tr>';
            foreach ($columns as $col) {
                echo "<th>$col</th>";
            }
            echo '</tr>';

            do {
                echo '<tr>';
                foreach ($columns as $col) {
                    echo "<td>{$row[$col]}</td>";
                }
                echo '</tr>';
            } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));

            echo '</table><br>';
        } else {
            echo "<p>No records found in $tableName!</p>";
        }
    } catch (PDOException $err) {
        echo "<p style='color:red'>Failed to retrieve $tableName: " . $err->getMessage() . "</p>";
    }
}

// Display all tables
displayTable($conn, 'Person', ['PersonID', 'FirstName', 'LastName', 'DOB', 'Gender']);
displayTable($conn, 'School', ['SchoolID', 'SchoolName', 'StreetName', 'Capacity', 'City', 'ZipCode']);
displayTable($conn, 'FamilyOwner', ['PersonID', 'Profession', 'FamilySize', 'Language', 'Bio']);
displayTable($conn, 'House', ['HouseID', 'StreetNumber', 'StreetName', 'City', 'ZipCode', 'IsPets', 'IsSmoking', 'NoOfAvailableRooms', 'TotalNumberOfRooms', 'OwnerID']);
displayTable($conn, 'RoomHouse', ['RoomNumber', 'HouseID', 'Rent', 'Size', 'StartDate', 'EndDate']);
displayTable($conn, 'Student', ['PersonID', 'Nationality', 'SchoolID', 'Year']);
displayTable($conn, 'Student_RoomHouse', ['RoomNumber', 'HouseID', 'StudentID']);
displayTable($conn, 'Contract', ['ContractID', 'StartDate', 'EndDate', 'StudentID', 'OwnerID']);
displayTable($conn, 'PersonWritesFeedback', ['PersonID', 'ContractID', 'Rating', 'FeedbackText', 'FeedbackDate']);
displayTable($conn, 'StudentHobbies', ['StudentID', 'Hobby']);
displayTable($conn, 'StudentInterests', ['StudentID', 'Interest']);

$conn = null;

echo "<br><a href='../index.html'>Back to the Homepage</a>";
?>
</body>
</html>
