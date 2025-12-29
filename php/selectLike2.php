<!doctype html>
<html>
<head>
    <title>Search Records by Keyword</title>
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { margin-top: 50px; }
    </style>
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

function displayTable($conn, $sql, $title) {
    echo "<h2>$title</h2>";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo "<table>";
            echo "<tr>";
            foreach ($row as $col => $val) echo "<th>$col</th>";
            echo "</tr>";
            do {
                echo "<tr>";
                foreach ($row as $val) echo "<td>$val</td>";
                echo "</tr>";
            } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
            echo "</table>";
        } else {
            echo "<p>No Records Found!</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red'>Query Failed: " . $e->getMessage() . "</p>";
    }
}

// ---------------- SEARCH QUERIES ----------------

// 1. House by StreetName
if(isset($_POST['stName'])) {
    displayTable($conn, 
        "SELECT HouseID, StreetNumber, StreetName, City, ZipCode, isPets, isSmoking, NoOfAvailableRooms, TotalNumberOfRooms 
         FROM House 
         WHERE StreetName LIKE '{$_POST['stName']}%'", 
        "Houses matching Street Name: " . htmlspecialchars($_POST['stName']));
}

// 2. RoomHouse by RoomNo
if(isset($_POST['roomNo'])) {
    displayTable($conn, 
        "SELECT RoomNo, HouseID, Price, Size, StartDate, EndDate 
         FROM RoomHouse 
         WHERE RoomNo LIKE '{$_POST['roomNo']}%'", 
        "Rooms matching Room Number: " . htmlspecialchars($_POST['roomNo']));
}

// 3. Student by Name
if(isset($_POST['studentName'])) {
    displayTable($conn, 
        "SELECT S.PersonID, P.FName, P.LName, S.Nationality, S.SchoolID, S.Year
         FROM Student S
         JOIN Person P ON S.PersonID = P.PersonID
         WHERE P.FName LIKE '{$_POST['studentName']}%' OR P.LName LIKE '{$_POST['studentName']}%'", 
        "Students matching Name: " . htmlspecialchars($_POST['studentName']));
}

// 4. FamilyOwner by Name
if(isset($_POST['ownerName'])) {
    displayTable($conn,
        "SELECT F.PersonID, P.FName, P.LName, F.Profession, F.FamilySize, F.Language
         FROM FamilyOwner F
         JOIN Person P ON F.PersonID = P.PersonID
         WHERE P.FName LIKE '{$_POST['ownerName']}%' OR P.LName LIKE '{$_POST['ownerName']}%'", 
        "Family Owners matching Name: " . htmlspecialchars($_POST['ownerName']));
}

// 5. Contract by ContractID
if(isset($_POST['contractID'])) {
    displayTable($conn,
        "SELECT ContractID, StartDate, EndDate, StudentID, OwnerID 
         FROM Contract 
         WHERE ContractID LIKE '{$_POST['contractID']}%'", 
        "Contracts matching Contract ID: " . htmlspecialchars($_POST['contractID']));
}

// 6. StudentHobbies by Hobby
if(isset($_POST['hobby'])) {
    displayTable($conn,
        "SELECT StudentID, Hobby 
         FROM StudentHobbies 
         WHERE Hobby LIKE '{$_POST['hobby']}%'", 
        "Students with Hobby matching: " . htmlspecialchars($_POST['hobby']));
}

// 7. StudentInterests by Interest
if(isset($_POST['interest'])) {
    displayTable($conn,
        "SELECT StudentID, Interest 
         FROM StudentInterests 
         WHERE Interest LIKE '{$_POST['interest']}%'", 
        "Students with Interest matching: " . htmlspecialchars($_POST['interest']));
}

// 8. PersonWritesFeedback by FeedbackText
if(isset($_POST['feedback'])) {
    displayTable($conn,
        "SELECT PersonID, ContractID, Rating, FeedbackText, FeedbackDate 
         FROM PersonWritesFeedback 
         WHERE FeedbackText LIKE '%{$_POST['feedback']}%'", 
        "Feedbacks matching keyword: " . htmlspecialchars($_POST['feedback']));
}

$conn = null;
echo "<br><a href='../index.html'>Back to Homepage</a>";
?>

</body>
</html>
