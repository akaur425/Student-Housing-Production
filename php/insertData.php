<?php
require 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>Connection Successful</p>";
} catch (PDOException $e) {
    die("<p style='color:red'>Connection Failed: " . $e->getMessage() . "</p>");
}

// -------------- Insert into Person --------------
if(isset($_POST['table']) && $_POST['table'] == 'Person') {
    $sql = "INSERT INTO Person (PersonID, FirstName, LastName, DOB, Gender) 
            VALUES (:pid, :fname, :lname, :dob, :gender)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pid', $_POST['PersonID']);
    $stmt->bindParam(':fname', $_POST['FirstName']);
    $stmt->bindParam(':lname', $_POST['LastName']);
    $stmt->bindParam(':dob', $_POST['DOB']);
    $stmt->bindParam(':gender', $_POST['Gender']);
}

// -------------- Insert into School --------------
if(isset($_POST['table']) && $_POST['table'] == 'School') {
    $sql = "INSERT INTO School (SchoolID, SchoolName, StreetName, Capacity, City, ZipCode) 
            VALUES (:sid, :sname, :street, :capacity, :city, :zip)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sid', $_POST['SchoolID']);
    $stmt->bindParam(':sname', $_POST['SchoolName']);
    $stmt->bindParam(':street', $_POST['StreetName']);
    $stmt->bindParam(':capacity', $_POST['Capacity']);
    $stmt->bindParam(':city', $_POST['City']);
    $stmt->bindParam(':zip', $_POST['ZipCode']);
}

// -------------- Insert into FamilyOwner --------------
if(isset($_POST['table']) && $_POST['table'] == 'FamilyOwner') {
    $sql = "INSERT INTO FamilyOwner (PersonID, Profession, FamilySize, Language, Bio) 
            VALUES (:pid, :prof, :fsize, :lang, :bio)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pid', $_POST['PersonID']);
    $stmt->bindParam(':prof', $_POST['Profession']);
    $stmt->bindParam(':fsize', $_POST['FamilySize']);
    $stmt->bindParam(':lang', $_POST['Language']);
    $stmt->bindParam(':bio', $_POST['Bio']);
}

// -------------- Insert into House --------------
if(isset($_POST['table']) && $_POST['table'] == 'House') {
    $sql = "INSERT INTO House (HouseID, StreetNumber, StreetName, City, ZipCode, IsPets, IsSmoking, NoOfAvailableRooms, TotalNumberOfRooms, OwnerID)
            VALUES (:hid, :stno, :stn, :city, :zip, :pets, :smoking, :avail, :total, :owner)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':hid', $_POST['HouseID']);
    $stmt->bindParam(':stno', $_POST['StreetNumber']);
    $stmt->bindParam(':stn', $_POST['StreetName']);
    $stmt->bindParam(':city', $_POST['City']);
    $stmt->bindParam(':zip', $_POST['ZipCode']);
    $stmt->bindParam(':pets', $_POST['IsPets']);
    $stmt->bindParam(':smoking', $_POST['IsSmoking']);
    $stmt->bindParam(':avail', $_POST['NoOfAvailableRooms']);
    $stmt->bindParam(':total', $_POST['TotalNumberOfRooms']);
    $stmt->bindParam(':owner', $_POST['OwnerID']);
}

// -------------- Insert into RoomHouse --------------
if(isset($_POST['table']) && $_POST['table'] == 'RoomHouse') {
    $sql = "INSERT INTO RoomHouse (RoomNumber, HouseID, Rent, Size, StartDate, EndDate) 
            VALUES (:rnum, :hid, :rent, :size, :start, :end)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':rnum', $_POST['RoomNumber']);
    $stmt->bindParam(':hid', $_POST['HouseID']);
    $stmt->bindParam(':rent', $_POST['Rent']);
    $stmt->bindParam(':size', $_POST['Size']);
    $stmt->bindParam(':start', $_POST['StartDate']);
    $stmt->bindParam(':end', $_POST['EndDate']);
}

// -------------- Insert into Student --------------
if(isset($_POST['table']) && $_POST['table'] == 'Student') {
    $sql = "INSERT INTO Student (PersonID, Nationality, SchoolID, Year) 
            VALUES (:pid, :nat, :sid, :year)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pid', $_POST['PersonID']);
    $stmt->bindParam(':nat', $_POST['Nationality']);
    $stmt->bindParam(':sid', $_POST['SchoolID']);
    $stmt->bindParam(':year', $_POST['Year']);
}

// -------------- Insert into Student_RoomHouse --------------
if(isset($_POST['table']) && $_POST['table'] == 'Student_RoomHouse') {
    $sql = "INSERT INTO Student_RoomHouse (RoomNumber, HouseID, StudentID) 
            VALUES (:rnum, :hid, :sid)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':rnum', $_POST['RoomNumber']);
    $stmt->bindParam(':hid', $_POST['HouseID']);
    $stmt->bindParam(':sid', $_POST['StudentID']);
}

// -------------- Insert into Contract --------------
if(isset($_POST['table']) && $_POST['table'] == 'Contract') {
    $sql = "INSERT INTO Contract (ContractID, StartDate, EndDate, StudentID, OwnerID)
            VALUES (:cid, :start, :end, :sid, :oid)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cid', $_POST['ContractID']);
    $stmt->bindParam(':start', $_POST['StartDate']);
    $stmt->bindParam(':end', $_POST['EndDate']);
    $stmt->bindParam(':sid', $_POST['StudentID']);
    $stmt->bindParam(':oid', $_POST['OwnerID']);
}

// -------------- Insert into PersonWritesFeedback --------------
if(isset($_POST['table']) && $_POST['table'] == 'PersonWritesFeedback') {
    $sql = "INSERT INTO PersonWritesFeedback (PersonID, ContractID, Rating, FeedbackText, FeedbackDate)
            VALUES (:pid, :cid, :rating, :text, :date)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':pid', $_POST['PersonID']);
    $stmt->bindParam(':cid', $_POST['ContractID']);
    $stmt->bindParam(':rating', $_POST['Rating']);
    $stmt->bindParam(':text', $_POST['FeedbackText']);
    $stmt->bindParam(':date', $_POST['FeedbackDate']);
}

// -------------- Insert into StudentHobbies --------------
if(isset($_POST['table']) && $_POST['table'] == 'StudentHobbies') {
    $sql = "INSERT INTO StudentHobbies (StudentID, Hobby) VALUES (:sid, :hobby)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sid', $_POST['StudentID']);
    $stmt->bindParam(':hobby', $_POST['Hobby']);
}

// -------------- Insert into StudentInterests --------------
if(isset($_POST['table']) && $_POST['table'] == 'StudentInterests') {
    $sql = "INSERT INTO StudentInterests (StudentID, Interest) VALUES (:sid, :interest)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sid', $_POST['StudentID']);
    $stmt->bindParam(':interest', $_POST['Interest']);
}

// Execute the prepared statement
if(isset($stmt)){
    try {
        $stmt->execute();
        echo "<p style='color:green'>Data Inserted Successfully into " . $_POST['table'] . "</p>";
    } catch(PDOException $e) {
        echo "<p style='color:red'>Insert Failed: " . $e->getMessage() . "</p>";
    }
}

echo "<a href='insert.html'>Insert More</a>";
echo "<br><a href='index.html'>Back to Homepage</a>";

$conn = null;
?>
