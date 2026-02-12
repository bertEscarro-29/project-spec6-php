<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];

if($_POST && isset($_POST['clearEntries'])){
    $_SESSION['input']['schoolID'] = null;
    $_SESSION['input']['schoolFullName'] = null;
    $_SESSION['input']['schoolShortName'] = null;
    $_SESSION['messages']['createSuccess'] = "";
    $_SESSION['messages']['createError'] = "";    

    $_SESSION['errors']['schoolID'] = "";
    $_SESSION['errors']['schoolFullName'] = "";
    $_SESSION['errors']['schoolShortName'] = "";

    header("Location: $entryURL", true, 301);
}

if($_POST && isset($_POST['saveNewSchoolEntry'])){
    $schoolID = $_POST['schoolID'];
    $schoolFullName = $_POST['schoolFullName'];
    $schoolShortName = $_POST['schoolShortName'];

    $_SESSION['input']['schoolID'] = $schoolID;
    $_SESSION['input']['schoolFullName'] = $schoolFullName;
    $_SESSION['input']['schoolShortName'] = $schoolShortName;

    if(!$_SESSION['errors']){
        $_SESSION['errors'] = [];
    }

    if(filter_input(INPUT_POST,'schoolID', FILTER_VALIDATE_INT) === false){
        $_SESSION['errors']['schoolID'] = "Invalid ID entry or format";
    } else {
        $_SESSION['errors']['schoolID'] = "";
    } 

    if(filter_input(INPUT_POST,'schoolFullName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['schoolFullName'] = "Invalid Full Name entry or format";
    } else {
        $_SESSION['errors']['schoolFullName'] = "";
    }

    if(filter_input(INPUT_POST,'schoolShortName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['schoolShortName'] = "Invalid Short Name entry or format";
    } else {
        $_SESSION['errors']['schoolShortName'] = "";
    }
    // check if school ID exists in the database
    $checkId = $db->prepare('SELECT * FROM colleges WHERE collid = ? ');
    $checkId->execute([
        $schoolID
    ]);
      if($checkId->rowCount() > 0){
            $_SESSION['errors']['schoolID'] = "ID  Is already Taken";
          
        }
    // check if school full name or short name already exists in the database
    $checkName = $db->prepare('SELECT * FROM colleges WHERE collfullname = ?');
    $checkName->execute([
        $schoolFullName
    ]);
  if($checkName->rowCount() > 0){
            $_SESSION['errors']['schoolFullName'] = "School Full Name is already Taken";
            $_SESSION['errors']['schoolShortName'] = "School Short Name is already Taken";
           
        }
// check if school full name or short name already exists in the database
    $checkShortName = $db->prepare('SELECT * FROM colleges WHERE collshortname = ?');
    $checkShortName->execute([
        $schoolShortName
    ]);
  if($checkShortName->rowCount() > 0){
            $_SESSION['errors']['schoolShortName'] = "School Short Name is already Taken";
           
        } 
 
    
if(!empty($_SESSION['errors']['schoolID']) || !empty($_SESSION['errors']['schoolFullName']) || !empty($_SESSION['errors']['schoolShortName'])){
        header("Location: $entryURL", true, 301);
        exit();

}


   

    if(empty($_SESSION['errors']['schoolID']) && empty($_SESSION['errors']['schoolFullName']) && empty($_SESSION['errors']['schoolShortName'])){
        $dbStatement = $db->prepare("INSERT INTO colleges (collid, collfullname, collshortname) VALUES (:collid, :collfullname, :collshortname)");
        $dbResult = $dbStatement->execute([
            'collid' => $schoolID,
            'collfullname' => $schoolFullName,
            'collshortname' => $schoolShortName
        ]);

        if($dbResult){
            $_SESSION['messages']['createSuccess'] = "School entry created successfully";
            $_SESSION['messages']['createError'] = "";
        } else {
            $_SESSION['messages']['createError'] = "Failed to create school entry";
            $_SESSION['messages']['createSuccess'] = "";
        }        

        header("Location: $entryURL", true, 301);
    } else {
        header("Location: $entryURL", true, 301);
    }
}