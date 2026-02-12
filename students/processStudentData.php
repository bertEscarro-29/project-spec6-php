<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];

if($_POST && isset($_POST['clearEntries'])){
    $_SESSION['input']['studentID'] = null;
    $_SESSION['input']['studentFirstName'] = null;
    $_SESSION['input']['studentMiddleName'] = null;
    $_SESSION['input']['studentLastName'] = null;
    $_SESSION['input']['studentCollID'] = null;
    $_SESSION['input']['programID'] = null;
    $_SESSION['input']['studyear'] = null;
    $_SESSION['input']['departmentID'] = null;
    


    $_SESSION['messages']['createSuccess'] = "";
    $_SESSION['messages']['createError'] = "";    

    $_SESSION['errors']['studentID'] = "";
    $_SESSION['errors']['studentFirstName'] = "";
    $_SESSION['errors']['studentMiddleName'] = "";
    $_SESSION['errors']['studentLastName'] = "";
    $_SESSION['errors']['studentCollID'] = "";
    $_SESSION['errors']['programID'] = "";
    $_SESSION['errors']['studyear'] = "";
    $_SESSION['errors']['departmentID'] = "";

    header("Location: $entryURL", true, 301);
}

if($_POST && isset($_POST['saveNewStudentEntry'])){
    $studentID = $_POST['studentID'];
    $studentFirstName = $_POST['studentFirstName'];
    $studentMiddleName = $_POST['studentMiddleName'];
    $studentLastName = $_POST['studentLastName'];
    $studentCollID = $_POST['studentCollID'];
    $programID = $_POST['programID'];
    $studyear = $_POST['studyear'];
    $departmentID = $_POST['departmentID'];
  

    $_SESSION['input']['studentID'] = $studentID;
    $_SESSION['input']['studentFirstName'] = $studentFirstName;
    $_SESSION['input']['studentMiddleName'] = $studentMiddleName;
    $_SESSION['input']['studentLastName'] = $studentLastName;
    $_SESSION['input']['studentCollID'] = $studentCollID;
    $_SESSION['input']['programID'] = $programID;
    $_SESSION['input']['studyear'] = $studyear;
    $_SESSION['input']['departmentID'] = $departmentID;

    if(!$_SESSION['errors']){
        $_SESSION['errors'] = [];
    }

    if(filter_input(INPUT_POST,'studentID', FILTER_VALIDATE_INT) === false){
        $_SESSION['errors']['studentID'] = "Invalid ID entry or format";
    } else {
        $_SESSION['errors']['studentID'] = "";
    } 

    if(filter_input(INPUT_POST,'studentFirstName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['studentFirstName'] = "Invalid First Name entry or format";
    } else {
        $_SESSION['errors']['studentFirstName'] = "";
    }

    if(filter_input(INPUT_POST,'studentMiddleName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['studentMiddleName'] = "Invalid Middle Name entry or format";
    } else {
        $_SESSION['errors']['studentMiddleName'] = "";
    }

    if(filter_input(INPUT_POST,'studentLastName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['studentLastName'] = "Invalid Last Name entry or format";
    } else {
        $_SESSION['errors']['studentLastName'] = "";
    }

    // check if student ID exists in the database
    $checkID = $db->prepare('SELECT * FROM students WHERE studid = ?');
    $checkID->execute([
        $studentID
    ]);
    if($checkID->rowCount() > 0){
        $_SESSION['errors']['studentID'] = "Student ID is already Taken";
    }

    // check if student full name already exists in the database
    $checkFullName = $db->prepare('SELECT * FROM students WHERE studfirstname = ? AND studmidname = ? AND studlastname = ?');
    $checkFullName->execute([
        $studentFirstName,
        $studentMiddleName,
        $studentLastName
    ]);
    if($checkFullName->rowCount() > 0){
        $_SESSION['errors']['studentFirstName'] = "Student Full Name is already Taken";
        $_SESSION['errors']['studentMiddleName'] = "Student Full Name is already Taken";
        $_SESSION['errors']['studentLastName'] = "Student Full Name is already Taken";
    }
    if(!empty($_SESSION['errors']['studentID']) || !empty($_SESSION['errors']['studentFirstName']) || !empty($_SESSION['errors']['studentMiddleName']) || !empty($_SESSION['errors']['studentLastName'])){
        header("Location: $entryURL", true, 301);
        exit();
    }



    if(empty($_SESSION['errors']['studentID']) && empty($_SESSION['errors']['studentFirstName']) && empty($_SESSION['errors']['studentMiddleName']) && empty($_SESSION['errors']['studentLastName'])){
        $dbStatement = $db->prepare("INSERT INTO students (studid, studfirstname, studmidname, studlastname, studcollid, studprogid,studyear, studcolldeptid) VALUES (:studid, :studfirstname, :studmidname, :studlastname, :studcollid, :studprogid, :studyear , :studcolldeptid)");
        $dbResult = $dbStatement->execute([
            'studid' => $studentID,
            'studfirstname' => $studentFirstName,
            'studmidname' => $studentMiddleName,
            'studlastname' => $studentLastName,
            'studcollid' => $studentCollID,
            'studprogid' => $programID,
            'studyear' => $studyear,
            'studcolldeptid' => $departmentID
        ]);

        if($dbResult){
            $_SESSION['messages']['createSuccess'] = "Student entry created successfully";
            $_SESSION['messages']['createError'] = "";
        } else {
            $_SESSION['messages']['createError'] = "Failed to create student entry";
            $_SESSION['messages']['createSuccess'] = "";
        }        

        header("Location: $entryURL", true, 301);
    } else {
        header("Location: $entryURL", true, 301);
    }
}