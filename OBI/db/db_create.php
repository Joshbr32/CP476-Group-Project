<?php

// Create Database 
function createSchema($conn){
  $sql = getSQLForDB();
 
  if ($conn->query($sql) === TRUE) {
    $conn->select_db("StudentGradesDB");
    echo "Database created successfully. Now Using 'StudentGradesDB'\n";
  } 
  else {
    echo "Error creating database: " . $conn->error;
  }
}

function getSQLForDB(){
  $sql = "CREATE DATABASE IF NOT EXISTS `StudentGradesDB`;";
  return $sql;
}
?>