<!doctype html>
<html>
<head>
    <title>Filtered Records</title>
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
$servername = "localhost";
$dbname = "HomeStayOrganizerCompanyMySQL";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>Connection Was Successful</p>";
} catch (PDOException $err) {
    die("<p style='color:red'>Connection Failed: " . $err->getMessage() . "</p>");
}

// Function to display filtered table
function displayFilteredTable($conn, $sql, $title) {
    echo "<h2>$title</h2>";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            echo "<table>";
            echo "<tr>";
            foreach ($row as $col => $val) { echo "<th>$col</th>"; }
            echo "</tr>";
            do {
                echo "<tr>";
                foreach ($row as $val) { echo "<td>$val</td>"; }
                echo "</tr>";
            } while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
            echo "</table>";
        } else {
            echo "<p>No Records Found!</p>";
        }
    } catch(PDOException $e) {
        echo "<p style='color:red'>Query Failed: " . $e->getMessage() . "</p>";
    }
}

// ------------------ FILTERED QUERIES FOR ALL TABLES ------------------

// 1. House by City
if(isset($_POST['houseCity'])) {
    displayFilteredTable($conn, 
        "SELECT HouseID, StreetNumber, StreetName, City, ZipCode, isPets, isSmoking, NoOfAvailableRooms, TotalNumberOfRooms 
        FROM House 
        WHERE City = '{$_POST['houseCity']}'",
        "Houses in City: " . htmlspecialchars($_POST['houseCity']));
}

// 2. House by Street
if(isset($_POST['houseStreet'])) {
    displayFilteredTable($conn, 
        "SELECT HouseID, StreetNumber, StreetName, City FROM House WHERE StreetName LIKE '{$_POST['houseStreet']}%'",
        "Houses on Street: " . htmlspecialchars($_POST['houseStreet']));
}

// 3. RoomHouse by HouseID
if(isset($_POST['houseIDRoom'])) {
    displayFilteredTable($conn,
        "SELECT RoomNo, HouseID, Price, Size, StartDate, EndDate 
        FROM RoomHouse 
        WHERE HouseID = '{$_POST['houseIDRoom']}'",
        "Rooms in HouseID: " . htmlspecialchars($_POST['houseIDRoom']));
}

// 4. Student by Name
if(isset($_POST['studentName'])) {
    displayFilteredTable($conn,
        "SELECT S.PersonID, P.FName, P.LName, S.Nationality, S.SchoolID, S.Year
        FROM Student S
        JOIN Person P ON S.PersonID = P.PersonID
        WHERE P.FName LIKE '{$_POST['studentName']}%' OR P.LName LIKE '{$_POST['studentName']}%'",
        "Students matching Name: " . htmlspecialchars($_POST['studentName']));
}

// 5. FamilyOwner by Name
if(isset($_POST['ownerName'])) {
    displayFilteredTable($conn,
        "SELECT F.PersonID, P.FName, P.LName, F.Profession, F.FamilySize, F.Language
        FROM FamilyOwner F
        JOIN Person P ON F.PersonID = P.PersonID
        WHERE P.FName LIKE '{$_POST['ownerName']}%' OR P.LName LIKE '{$_POST['ownerName']}%'",
        "Family Owners matching Name: " . htmlspecialchars($_POST['ownerName']));
}

// 6. Contract by StudentID
if(isset($_POST['studentIDContract'])) {
    displayFilteredTable($conn,
        "SELECT ContractID, StartDate, EndDate, StudentID, OwnerID 
        FROM Contract 
        WHERE StudentID = '{$_POST['studentIDContract']}'",
        "Contracts for StudentID: " . htmlspecialchars($_POST['studentIDContract']));
}

// 7. Contract by OwnerID
if(isset($_POST['ownerIDContract'])) {
    displayFilteredTable($conn,
        "SELECT ContractID, StartDate, EndDate, StudentID, OwnerID 
        FROM Contract 
        WHERE OwnerID = '{$_POST['ownerIDContract']}'",
        "Contracts for OwnerID: " . htmlspecialchars($_POST['ownerIDContract']));
}

// 8. StudentHobbies by StudentID
if(isset($_POST['studentIDHobby'])) {
    displayFilteredTable($conn,
        "SELECT StudentID, Hobby 
        FROM StudentHobbies 
        WHERE StudentID = '{$_POST['studentIDHobby']}'",
        "Hobbies of StudentID: " . htmlspecialchars($_POST['studentIDHobby']));
}

// 9. StudentInterests by StudentID
if(isset($_POST['studentIDInterest'])) {
    displayFilteredTable($conn,
        "SELECT StudentID, Interest 
        FROM StudentInterests 
        WHERE StudentID = '{$_POST['studentIDInterest']}'",
        "Interests of StudentID: " . htmlspecialchars($_POST['studentIDInterest']));
}

// 10. PersonWritesFeedback by PersonID
if(isset($_POST['personIDFeedback'])) {
    displayFilteredTable($conn,
        "SELECT PersonID, ContractID, Rating, FeedbackText, FeedbackDate 
        FROM PersonWritesFeedback 
        WHERE PersonID = '{$_POST['personIDFeedback']}'",
        "Feedbacks by PersonID: " . htmlspecialchars($_POST['personIDFeedback']));
}

$conn = null;
echo "<br><a href='../index.html'>Back to Homepage</a>";
?>
</body>
</html>
