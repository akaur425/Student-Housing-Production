<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Result</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1>Query Result</h1>

<?php
require 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<p style='color:red'>Connection Failed: " . $e->getMessage() . "</p>");
}

// List of queries
$queries = [
    1 => "CREATE OR REPLACE VIEW StudentSameHouse AS
          SELECT S1.StudentID AS Student1, S2.StudentID AS Student2, S1.HouseID AS HID
          FROM Student_RoomHouse S1, Student_RoomHouse S2
          WHERE S1.StudentID < S2.StudentID AND S1.HouseID = S2.HouseID;

          SELECT P1.FName AS Student1F, P1.LName AS Student1L,
                 P2.FName AS Student2F, P2.LName AS Student2L,
                 O.FName AS OwnerF, O.LName AS OwnerL
          FROM StudentSameHouse SSH
          JOIN Person P1 ON SSH.Student1 = P1.PersonID
          JOIN Person P2 ON SSH.Student2 = P2.PersonID
          JOIN House ON SSH.HID = House.HouseID
          JOIN Person O ON O.PersonID = House.OwnerID;",
    2 => "SELECT RoomHouse.RoomNo, RoomHouse.HouseID,
                 (SELECT FName FROM Person WHERE PersonID = Student_RoomHouse.StudentID) AS SFName,
                 (SELECT LName FROM Person WHERE PersonID = Student_RoomHouse.StudentID) AS SLName
          FROM RoomHouse LEFT JOIN Student_RoomHouse
          ON RoomHouse.RoomNo = Student_RoomHouse.RoomNo
          AND RoomHouse.HouseID = Student_RoomHouse.HouseID;",
    3 => "SELECT (SELECT FName FROM Person WHERE PersonID = Student.PersonID) AS FName,
                 (SELECT LName FROM Person WHERE PersonID = Student.PersonID) AS LName
          FROM Student
          WHERE PersonID NOT IN (SELECT StudentID FROM Student_RoomHouse);",
    // ... continue adding all other queries ...
    4 => 
        "SELECT RoomHouse.RoomNo, RoomHouse.HouseID
        FROM RoomHouse INNER JOIN Student_RoomHouse
        ON RoomHouse.RoomNo = Student_RoomHouse.RoomNo
        AND RoomHouse.HouseID = Student_RoomHouse.HouseID
        WHERE Price > ANY(
            SELECT AVG(Price)
            FROM RoomHouse INNER JOIN Student_RoomHouse
            ON RoomHouse.RoomNo = Student_RoomHouse.RoomNo
            AND RoomHouse.HouseID = Student_RoomHouse.HouseID
            GROUP BY Student_RoomHouse.StudentID
        );",

    5 => "SELECT House.HouseID, COUNT(*) AS TotalNumberOFRoomsOccupied,
               (SELECT FName FROM Person WHERE PersonID = House.OwnerID) AS OFName,
               (SELECT LName FROM Person WHERE PersonID = House.OwnerID) AS OLName
        FROM House, RoomHouse, Student_RoomHouse
        WHERE House.HouseID = RoomHouse.HouseID
          AND RoomHouse.RoomNo = Student_RoomHouse.RoomNo
          AND RoomHouse.HouseID = Student_RoomHouse.HouseID
        GROUP BY HouseID, OFName, OLName;",

    6 => "SELECT S.PersonID, P.FName, P.LName, SH.Hobby AS Hobbies, SI.Interest AS Interests
        FROM Student S
        INNER JOIN Person P ON S.PersonID = P.PersonID
        INNER JOIN StudentHobbies SH ON S.PersonID = SH.StudentID
        INNER JOIN StudentInterests SI ON S.PersonID = SI.StudentID
        ORDER BY S.PersonID;",

    7 => 
        "CREATE OR REPLACE VIEW PriceLT750 AS
        SELECT Price, RoomNo, HouseID FROM RoomHouse
        WHERE Price<=750 AND Price IS NOT NULL;

        SELECT RoomHouse.RoomNo, RoomHouse.HouseID, PriceLT750.Price
        FROM RoomHouse
        INNER JOIN PriceLT750
          ON RoomHouse.HouseID = PriceLT750.HouseID
          AND RoomHouse.RoomNo = PriceLT750.RoomNo
        WHERE EXISTS (
            SELECT HouseID FROM House
            WHERE RoomHouse.HouseID = House.HouseID
              AND isSmoking = 1 AND isPets = 1
        );",

    8 =>
        "SELECT FName, LName
        FROM Person JOIN Student S1 ON Person.PersonID = S1.PersonID
        WHERE NOT EXISTS (
            SELECT 1 FROM Student S2
            WHERE S1.PersonID <> S2.PersonID
              AND S1.InstitutionNumber = S2.InstitutionNumber
        );",

    9 => 
        "SELECT FName, LName
        FROM Person INNER JOIN PersonWritesFeedback
        ON Person.PersonID = PersonWritesFeedback.PersonID
        WHERE Rating >= ALL (SELECT Rating FROM PersonWritesFeedback);",

    10 => 
        "SELECT ContractID,
               (SELECT FName FROM Person WHERE PersonID = Contract.StudentID) AS SFName,
               (SELECT LName FROM Person WHERE PersonID = Contract.StudentID) AS SLName,
               (SELECT FName FROM Person WHERE PersonID = Contract.OwnerID) AS OFName,
               (SELECT LName FROM Person WHERE PersonID = Contract.OwnerID) AS OLName
        FROM Contract
        WHERE EndDate < '2025-03-15';",

    11 => 
        "SELECT (SELECT FName FROM Person WHERE PersonID = Contract.OwnerID) AS OFName,
               (SELECT LName FROM Person WHERE PersonID = Contract.OwnerID) AS OLName,
               COUNT(OwnerID) AS NoOfContracts
        FROM Contract
        WHERE NOT EndDate < '2025-03-15'
        GROUP BY OwnerID;",

    12 => 
        "SELECT FamilyOwner.PersonID, FName, LName
        FROM FamilyOwner JOIN Person ON Person.PersonID = FamilyOwner.PersonID
        WHERE FamilyOwner.PersonID NOT IN (SELECT OwnerID FROM Contract);",

    13 => 
        "SELECT RoomHouse.HouseID, RoomHouse.RoomNo
        FROM Student_RoomHouse RIGHT JOIN RoomHouse
        ON Student_RoomHouse.RoomNo <> RoomHouse.RoomNo AND Student_RoomHouse.HouseID <> RoomHouse.HouseID
        WHERE RoomHouse.Price IS NOT NULL;",

    14 => 
        "SELECT DISTINCT RoomHouse.HouseID, (SELECT OwnerID FROM House WHERE HouseID = RoomHouse.HouseID) AS OwnerID
        FROM RoomHouse LEFT JOIN Student_RoomHouse
        ON RoomHouse.RoomNo <> Student_RoomHouse.RoomNo AND RoomHouse.HouseID <> Student_RoomHouse.HouseID
        WHERE Price IS NOT NULL;",

    15 => 
        "SELECT RoomHouse.RoomNo, RoomHouse.HouseID, RoomHouse.Price, House.City, House.StreetName
        FROM RoomHouse
        JOIN House ON RoomHouse.HouseID = House.HouseID
        WHERE House.City = 'Surrey'
        AND RoomHouse.Price = (
            SELECT MIN(Price) FROM RoomHouse
            JOIN House ON RoomHouse.HouseID = House.HouseID
            WHERE House.City = 'Surrey'
        );",

    16 => 
        "SELECT DISTINCT StudentID,
               (SELECT FName FROM Person WHERE PersonID = Student_RoomHouse.StudentID) AS SName
        FROM School INNER JOIN House ON School.City = House.City
        INNER JOIN Student_RoomHouse ON Student_RoomHouse.HouseID = House.HouseID
        INNER JOIN Student ON Student_RoomHouse.StudentID = Student.PersonID
           AND School.InstitutionNumber = Student.InstitutionNumber;",

    17 => 
        "SELECT Person.PersonID, Person.FName, Person.LName
        FROM Person
        WHERE Person.PersonID IN (
            SELECT OwnerID FROM House
            GROUP BY OwnerID
            HAVING COUNT(OwnerID) > 1
        );",

    18 => 
        "SELECT OwnerID, COUNT(ContractID) AS ContractCount
        FROM Contract
        GROUP BY OwnerID
        HAVING COUNT(ContractID)>=ALL(
            SELECT COUNT(ContractID) FROM Contract GROUP BY OwnerID
        );"
];

// Get the query number from URL
$queryNumber = isset($_GET['query']) ? intval($_GET['query']) : 0;

if ($queryNumber && isset($queries[$queryNumber])) {
    $query = $queries[$queryNumber];
    echo "<h2>Query $queryNumber Result</h2>";

    try {
        $stmt = $conn->query($query);
        if($stmt){
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($rows){
                echo "<table border='1' cellpadding='5'>";
                // Header
                echo "<tr>";
                foreach(array_keys($rows[0]) as $col){
                    echo "<th>$col</th>";
                }
                echo "</tr>";
                // Data
                foreach($rows as $row){
                    echo "<tr>";
                    foreach($row as $val){
                        echo "<td>$val</td>";
                    }
                    echo "</tr>";
                }
                echo "</table><br>";
            } else {
                echo "<p>No records found</p>";
            }
        }
    } catch(PDOException $e){
        echo "<p style='color:red'>Query failed: ".$e->getMessage()."</p>";
    }

} else {
    echo "<p style='color:red'>Invalid query selected.</p>";
}

$conn = null;
?>

<br><a href="queryList.html">Back to Query List</a>

</body>
</html>

