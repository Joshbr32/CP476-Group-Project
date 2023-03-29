<?php

function insertRecords($conn) {
    $sql = getSQLForRecords();


    // Add records to table
    $result = $conn->multi_query($sql);

    // Needed to allow multiquery function
    while($conn->next_result()) continue;

    if ($result) {
        echo "Records created successfully\n";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
}

// Generate the SQL code for adding records to the database
function getSQLForRecords() {
    $sql = "DELETE FROM `Course_Table`;";
    $sql .= "DELETE FROM `Name_Table`;";

    // Insert Records into Name Table
    $sql .= "
    INSERT INTO Name_Table (Student_ID, Student_Name)
    VALUES 
        (308621686, 'Boone Stevenson'),
        (448227065, 'Micheal Conrad'),
        (309251919, 'Kayla Conway'),
        (350971244, 'Belinda Bain'),
        (415807676, 'Autumn Schmidt'),
        (603077700, 'Rahul Prosser'),
        (547161604, 'Ayyan Whiteley'),
        (187509717, 'Ameena Khan'),
        (309663833, 'Bertram Smith'),
        (293688639, 'Dominique Lovel'),
        (570797438, 'Minnie Rivers'),
        (403966911, 'Liang Yu'),
        (559545416, 'Alexander Floydd'),
        (503239671, 'Matthew Hall'),
        (256047895, 'Lori Donovan'),
        (301758883, 'Ellie-May Palmer'),
        (627137015, 'Keaton Sheppard'),
        (429464715, 'Tiago Rivera'),
        (458362883, 'Krishan Patel'),
        (280587734, 'Kendra Paul'),
        (613465484, 'Leonard Whitehead'),
        (154102471, 'James Andersen'),
        (397016834, 'Hermione Bullock'),
        (505004484, 'Emran Bashir'),
        (251173274, 'Xiao Qiang');
    ";

    // Insert Records into Course table
    $sql .= "
    INSERT INTO Course_Table (Student_ID, Course_Code, Test_1, Test_2, Test_3, Final_Exam)
    VALUES 
        (280587734, 'PS272', 74, 98, 76, 52),
        (280587734, 'CH202', 66, 82, 81, 75),
        (256047895, 'MA222', 69, 80, 72, 87),
        (154102471, 'CP465', 63, 82, 58, 68),
        (187509717, 'CP202', 58, 98, 56, 89),
        (503239671, 'ST262', 66, 84, 95, 88),
        (448227065, 'CP465', 59, 69, 56, 96),
        (429464715, 'CH120', 54, 93, 71, 80),
        (627137015, 'EC140', 85, 56, 72, 77),
        (415807676, 'EC140', 70, 89, 90, 63),
        (293688639, 'CH120', 85, 80, 78, 83),
        (256047895, 'EC140', 66, 53, 85, 50),
        (350971244, 'BU121', 55, 96, 75, 95),
        (308621686, 'EC140', 92, 90, 50, 89),
        (570797438, 'CP321', 81, 71, 65, 96),
        (505004484, 'ST494', 70, 95, 54, 62),
        (251173274, 'PS275', 86, 64, 65, 59),
        (397016834, 'CP220', 84, 78, 55, 55),
        (547161604, 'CP202', 70, 82, 81, 56),
        (293688639, 'BU121', 69, 85, 68, 95),
        (429464715, 'EC140', 57, 85, 71, 84),
        (505004484, 'MA222', 63, 88, 85, 87),
        (301758883, 'CP321', 68, 78, 55, 88),
        (559545416, 'PS275', 74, 70, 86, 65),
        (503239671, 'CP465', 68, 61, 62, 61),
        (403966911, 'CH120', 52, 89, 92, 66),
        (448227065, 'CH261', 80, 54, 55, 93),
        (251173274, 'EC140', 98, 58, 59, 52),
        (154102471, 'MA238', 55, 94, 84, 93),
        (397016834, 'CH120', 75, 95, 83, 67),
        (187509717, 'ST490', 83, 65, 63, 93),
        (308621686, 'ST262', 87, 76, 87, 67),
        (603077700, 'CH202', 78, 81, 92, 91),
        (415807676, 'CH120', 94, 72, 55, 70),
        (403966911, 'PS275', 89, 57, 81, 87),
        (309251919, 'CP220', 90, 71, 62, 65),
        (458362883, 'ST494', 51, 96, 67, 57),
        (627137015, 'PS275', 95, 64, 73, 60),
        (309663833, 'CH120', 71, 77, 53, 56),
        (309251919, 'CP321', 59, 94, 64, 52),
        (547161604, 'CP220', 86, 90, 84, 88),
        (350971244, 'EC140', 93, 54, 51, 57),
        (559545416, 'ST262', 90, 62, 88, 86),
        (613465484, 'MA222', 57, 75, 55, 73),
        (458362883, 'MA238', 57, 52, 88, 51),
        (613465484, 'EC140', 62, 61, 95, 57),
        (603077700, 'PS275', 80, 63, 96, 53),
        (309663833, 'ST262', 93, 51, 84, 77),
        (570797438, 'CP220', 89, 56, 66, 54),
        (301758883, 'MA222', 64, 54, 91, 69);
    ";

    return $sql;
}
?>