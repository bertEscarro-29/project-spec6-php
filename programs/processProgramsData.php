<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];

if($_POST && isset($_POST['clearEntries'])){
    $_SESSION['input']['programID'] = null;
    $_SESSION['input']['programFullName'] = null;
    $_SESSION['input']['programShortName'] = null;
    $_SESSION['input']['departmentID'] = null;
    $_SESSION['input']['collegeID'] = null;
    $_SESSION['messages']['createSuccess'] = "";
    $_SESSION['messages']['createError'] = "";    

    $_SESSION['errors']['programID'] = "";
    $_SESSION['errors']['programFullName'] = "";
    $_SESSION['errors']['programShortName'] = "";
    $_SESSION['errors']['departmentID'] = "";
    $_SESSION['errors']['collegeID'] = "";

    header("Location: $entryURL", true, 301);
}

if($_POST && isset($_POST['saveNewProgramEntry'])){
    $programID = $_POST['programID'];
    $programFullName = $_POST['programFullName'];
    $programShortName = $_POST['programShortName'];
    $departmentID = $_POST['departmentID'];
    $collegeID = $_POST['collegeID'];

    $_SESSION['input']['programID'] = $programID;
    $_SESSION['input']['programFullName'] = $programFullName;
    $_SESSION['input']['programShortName'] = $programShortName;
    $_SESSION['input']['departmentID'] = $departmentID;
    $_SESSION['input']['collegeID'] = $collegeID;

    if(!$_SESSION['errors']){
        $_SESSION['errors'] = [];
    }

    if(filter_input(INPUT_POST,'programID', FILTER_VALIDATE_INT) === false){
        $_SESSION['errors']['programID'] = "Invalid ID entry or format";
    } else {
        $_SESSION['errors']['programID'] = "";
    } 

    if(filter_input(INPUT_POST,'programFullName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['programFullName'] = "Invalid Full Name entry or format";
    } else {
        $_SESSION['errors']['programFullName'] = "";
    }

    if(filter_input(INPUT_POST,'programShortName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['programShortName'] = "Invalid Short Name entry or format";
    } else {
        $_SESSION['errors']['programShortName'] = "";
    }

    //check if program ID exists in the database
    $checkProgramID = $db->prepare("SELECT * FROM programs WHERE progid = :progid");
    $checkProgramID->execute(['progid' => $programID]);
    if($checkProgramID->fetch()){
        $_SESSION['errors']['programID'] = "Program ID already exists. Please enter a unique Program ID.";
    } 

    //check if program full name exists in the database
    $checkProgramFullName = $db->prepare("SELECT * FROM programs WHERE progfullname = :progfullname");
    $checkProgramFullName->execute(['progfullname' => $programFullName]);
    if($checkProgramFullName->fetch()){
        $_SESSION['errors']['programFullName'] = "Program Full Name already exists. Please enter a unique Program Full Name.";
    }
    //check if program short name exists in the database
    $checkProgramShortName = $db->prepare("SELECT * FROM programs WHERE progshortname = :progshortname");
    $checkProgramShortName->execute(['progshortname' => $programShortName]);
    if($checkProgramShortName->fetch()){
        $_SESSION['errors']['programShortName'] = "Program Short Name already exists. Please enter a unique Program Short Name.";
    }

    // check errors before database entry
    if(!empty($_SESSION['errors']['programID']) || !empty($_SESSION['errors']['programFullName']) || !empty($_SESSION['errors']['programShortName']) || !empty($_SESSION['errors']['departmentID']) || !empty($_SESSION['errors']['collegeID'])){
       header("Location: $entryURL", true, 301);
       exit();
    }

    if(empty($_SESSION['errors']['programID']) && empty($_SESSION['errors']['programFullName']) && empty($_SESSION['errors']['programShortName']) && empty($_SESSION['errors']['departmentID']) && empty($_SESSION['errors']['collegeID'])){
        $dbStatement = $db->prepare("INSERT INTO programs (progid, progfullname, progshortname, progcollid, progcolldeptid) VALUES (:progid, :progfullname, :progshortname, :progcollid, :progcolldeptid)");
        $dbResult = $dbStatement->execute([
            'progid' => $programID,
            'progfullname' => $programFullName,
            'progshortname' => $programShortName,
            'progcollid' => $collegeID,
            'progcolldeptid' => $departmentID
        ]);

        if($dbResult){
            $_SESSION['messages']['createSuccess'] = "Program entry created successfully";
            $_SESSION['messages']['createError'] = "";
        } else {
            $_SESSION['messages']['createError'] = "Failed to create program entry";
            $_SESSION['messages']['createSuccess'] = "";
        }        

        header("Location: $entryURL", true, 301);
    } else {
        header("Location: $entryURL", true, 301);
    }
}