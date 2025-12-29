<!doctype html>
<html>

<head>
    <title>Create Database and Tables</title>
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>

<?php
require 'config.php';

try {
    // Connect to MySQL server without specifying database
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>Connected to MySQL server successfully.</p>";
} catch (PDOException $err) {
    die("<p style='color:red'>Connection failed: " . $err->getMessage() . "</p>");
}

try {
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS HomestayOrganizerCompanyMySQL";
    $conn->exec($sql);
    echo "<p style='color:green'>Database created successfully.</p>";

    // Use the new database
    $conn->exec("USE HomestayOrganizerCompanyMySQL");

    // Create all tables exactly as in your SQL
    $tables = [

        "CREATE TABLE Person(
            PersonID INT,
            FName VARCHAR(15) NOT NULL,
            LName VARCHAR(15) NOT NULL,
            DateOfBirth DATE,
            Gender CHAR(1),
            PRIMARY KEY(PersonID)
        )",

        "CREATE TABLE School(
            InstitutionNumber INT, 
            SchoolName VARCHAR(30) NOT NULL,
            StreetName VARCHAR(30),
            StreetNumber INT,
            City VARCHAR(15), 
            ZipCode CHAR(6), 
            PRIMARY KEY(InstitutionNumber)
        )",

        "CREATE TABLE FamilyOwner(
            PersonID INT,
            Occupation VARCHAR(20) NOT NULL,
            FamilySize SMALLINT NOT NULL,
            LanguageSpoken CHAR(8),
            Biography TEXT,
            PRIMARY KEY(PersonID),
            FOREIGN KEY(PersonID) REFERENCES Person(PersonID) ON DELETE CASCADE
        )",

        "CREATE TABLE House(
            HouseID INT,
            StreetNumber INT,
            StreetName VARCHAR(20),
            City VARCHAR(15),
            ZipCode CHAR(6),
            isPets BIT NOT NULL,
            isSmoking BIT NOT NULL,
            NoOfAvailableRooms INT NOT NULL,
            TotalNumberOfRooms INT NOT NULL,
            OwnerID INT,
            PRIMARY KEY(HouseID),
            FOREIGN KEY(OwnerID) REFERENCES FamilyOwner(PersonID) ON DELETE CASCADE
        )",

        "CREATE TABLE Student(
            PersonID INT,
            Nationality VARCHAR(10),
            InstitutionNumber INT NOT NULL,
            DurationOfStudiesInYears SMALLINT NOT NULL,
            PRIMARY KEY(PersonID),
            FOREIGN KEY(InstitutionNumber) REFERENCES School(InstitutionNumber) ON DELETE CASCADE,
            FOREIGN KEY(PersonID) REFERENCES Person(PersonID) ON DELETE CASCADE
        )",

        "CREATE TABLE RoomHouse(
            RoomNo INT NOT NULL,
            HouseID INT NOT NULL,
            Price DECIMAL(10, 2),
            Size FLOAT NOT NULL,
            StartDate DATE,
            EndDate DATE,
            PRIMARY KEY(RoomNo, HouseID),
            FOREIGN KEY(HouseID) REFERENCES House(HouseID) ON DELETE CASCADE
        )",

        "CREATE TABLE Student_RoomHouse(
            RoomNo INT,
            HouseID INT,
            StudentID INT,
            PRIMARY KEY(RoomNo, HouseID),
            FOREIGN KEY(RoomNo, HouseID) REFERENCES RoomHouse(RoomNo, HouseID) ON DELETE CASCADE,
            FOREIGN KEY(StudentID) REFERENCES Student(PersonID) ON DELETE CASCADE
        )",

        "CREATE TABLE Contract(
            ContractID INT,
            StartDate DATE,
            EndDate DATE,
            StudentID INT,
            OwnerID INT,
            PRIMARY KEY(ContractID),
            FOREIGN KEY(StudentID) REFERENCES Student(PersonID) ON DELETE SET NULL,
            FOREIGN KEY(OwnerID) REFERENCES FamilyOwner(PersonID) ON DELETE SET NULL
        )",

        "CREATE TABLE PersonWritesFeedback(
            PersonID INT,
            ContractID INT NOT NULL,
            Rating DECIMAL(2,1) CHECK(NOT(Rating<0 AND Rating>5)),
            CommentText TEXT,
            CommentDate DATE,
            PRIMARY KEY(PersonID, ContractID),
            FOREIGN KEY(ContractID) REFERENCES Contract(ContractID) ON DELETE CASCADE,
            FOREIGN KEY(PersonID) REFERENCES Person(PersonID) ON DELETE CASCADE
        )",

        "CREATE TABLE StudentHobbies(
            StudentID INT NOT NULL,
            Hobbies VARCHAR(30),
            PRIMARY KEY(StudentID, Hobbies),
            FOREIGN KEY(StudentID) REFERENCES Person(PersonID) ON DELETE CASCADE
        )",

        "CREATE TABLE StudentInterests(
            StudentID INT NOT NULL,
            Interests VARCHAR(30),
            PRIMARY KEY(StudentID, Interests),
            FOREIGN KEY(StudentID) REFERENCES Person(PersonID) ON DELETE CASCADE
        )"
    ];

    foreach ($tables as $sql) {
        $conn->exec($sql);
    }

    echo "<p style='color:green'>All tables created successfully.</p>";

} catch (PDOException $err) {
    echo "<p style='color:red'>Error creating database/tables: " . $err->getMessage() . "</p>";
}

// Close connection
unset($conn);

echo "<a href='../index.html'>Back to the Homepage</a>";
?>

</body>
</html>
