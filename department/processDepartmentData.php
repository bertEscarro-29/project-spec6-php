<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];

if($_POST && isset($_POST['clearEntries'])){
    $_SESSION['input']['departmentID'] = null;
    $_SESSION['input']['departmentFullName'] = null;
    $_SESSION['input']['departmentShortName'] = null;
    $_SESSION['messages']['createSuccess'] = "";
    $_SESSION['messages']['createError'] = "";    

    $_SESSION['errors']['departmentID'] = "";
    $_SESSION['errors']['departmentFullName'] = "";
    $_SESSION['errors']['departmentShortName'] = "";
    $_SESSION['errors']['departmentCollegeID'] = "";

    header("Location: $entryURL", true, 301);
}

if($_POST && isset($_POST['saveNewDepartmentEntry'])){
    $departmentID = $_POST['departmentID'];
    $departmentFullName = $_POST['departmentFullName'];
    $departmentShortName = $_POST['departmentShortName'];

    $_SESSION['input']['departmentID'] = $departmentID;
    $_SESSION['input']['departmentFullName'] = $departmentFullName;
    $_SESSION['input']['departmentShortName'] = $departmentShortName;
    $_SESSION['input']['departmentCollegeID'] = $_POST['departmentCollegeID'];

    if(!$_SESSION['errors']){
        $_SESSION['errors'] = [];
    }

    if(filter_input(INPUT_POST,'departmentID', FILTER_VALIDATE_INT) === false){
        $_SESSION['errors']['departmentID'] = "Invalid ID entry or format";
    } else {
        $_SESSION['errors']['departmentID'] = "";
    } 

    if(filter_input(INPUT_POST,'departmentFullName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['departmentFullName'] = "Invalid Full Name entry or format";
    } else {
        $_SESSION['errors']['departmentFullName'] = "";
    }

    if(filter_input(INPUT_POST,'departmentShortName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['departmentShortName'] = "Invalid Short Name entry or format";
    } else {
        $_SESSION['errors']['departmentShortName'] = "";
    }

    // check if id 
    $checkID = $db->prepare("SELECT * FROM departments WHERE deptid = :deptid");
$checkID->execute(['deptid' => $departmentID]);
if($checkID->fetch()){
    $_SESSION['errors']['departmentID'] = "Department ID already exists. Please choose a different ID.";
    $_SESSION['messages']['createSuccess'] = "";
  
}

//check if department full name exists in the database
$checkFullName = $db->prepare("SELECT * FROM departments WHERE deptfullname = :deptfullname");
$checkFullName->execute(['deptfullname' => $departmentFullName]);
if($checkFullName->fetch()){
    $_SESSION['errors']['departmentFullName'] = "Department Full Name already exists. Please choose a different name.";
    $_SESSION['messages']['createSuccess'] = "";
   
}

//check if department short name exists in the database
$checkShortName = $db->prepare("SELECT * FROM departments WHERE deptshortname = :deptshortname");
$checkShortName->execute(['deptshortname' => $departmentShortName]);
if($checkShortName->fetch()){
    $_SESSION['errors']['departmentShortName'] = "Department Short Name already exists. Please choose a different name.";
    $_SESSION['messages']['createSuccess'] = "";
  
}


// check errors before redirecting to the entry page
if(!empty($_SESSION['errors']['departmentID']) || !empty($_SESSION['errors']['departmentFullName']) || !empty($_SESSION['errors']['departmentShortName']) || !empty($_SESSION['errors']['departmentCollegeID'])){
    header("Location: $entryURL", true, 301);
    exit();
}


    if(empty($_SESSION['errors']['departmentID']) && empty($_SESSION['errors']['departmentFullName']) && empty($_SESSION['errors']['departmentShortName']) && empty($_SESSION['errors']['departmentCollegeID'])){
        
    



    $dbStatement = $db->prepare("INSERT INTO departments (deptid, deptfullname, deptshortname, deptcollid) VALUES (:deptid, :deptfullname, :deptshortname, :deptcollid)");
        $dbResult = $dbStatement->execute([
            'deptid' => $departmentID,
            'deptfullname' => $departmentFullName,
            'deptshortname' => $departmentShortName,
            'deptcollid' => $_SESSION['input']['departmentCollegeID']
        ]);

        if($dbResult){
            $_SESSION['messages']['createSuccess'] = "Department entry created successfully";
            $_SESSION['messages']['createError'] = "";
        } else {
            $_SESSION['messages']['createError'] = "Failed to create department entry";
            $_SESSION['messages']['createSuccess'] = "";
        }        

        header("Location: $entryURL", true, 301);




    } else {
        header("Location: $entryURL", true, 301);
    }
}