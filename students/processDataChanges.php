<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];

if($_POST && isset($_POST['clearChanges'])){
    $_SESSION['errors']['studFirstName'] = "";
    $_SESSION['errors']['studMiddleName'] = "";
    $_SESSION['errors']['studLastName'] = "";
    $_SESSION['messages']['updateSuccess'] = "";
    $_SESSION['messages']['updateError'] = "";

    header("Location: $entryURL", true, 301);
}

if($_POST && isset($_POST['saveChanges'])){
    $studID = $_POST['studId'];
    $studFirstName = $_POST['studFirstName'];
    $studMiddleName = $_POST['studMiddleName'];
    $studLastName = $_POST['studLastName'];

    $_SESSION['input']['studFirstName'] = $studFirstName;
    $_SESSION['input']['studMiddleName'] = $studMiddleName;
    $_SESSION['input']['studLastName'] = $studLastName;

    if(isset($_SESSION['errors'])){
        $_SESSION['errors'] = [];
    }

    if(filter_input(INPUT_POST,'studFirstName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['studFirstName'] = "Invalid First Name entry. Reverting to original value";
    } else {
        $_SESSION['errors']['studFirstName'] = "";
    }

    if(filter_input(INPUT_POST,'studMiddleName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['studMiddleName'] = "Invalid Middle Name entry. Reverting to original value";
    } else {
        $_SESSION['errors']['studMiddleName'] = "";
    }

       $dbStatement = $db->prepare('SELECT * FROM students WHERE studfirstname = ? AND studmidname = ? AND studlastname = ?');
    $dbStatement->execute([
        $studFirstName,
        $studMiddleName,
        $studLastName
    ]);
    if($dbStatement->rowCount() > 0){
            $_SESSION['errors']['studFirstName'] = "Same Data, Ignoring update to this field";
            $_SESSION['errors']['studMiddleName'] = "Same Data, Ignoring update to this field";
            $_SESSION['errors']['studLastName'] = "Same Data, Ignoring update to this field";
            header("Location: $entryURL", true, 301);
            exit();
        }
    

    if(filter_input(INPUT_POST,'studLastName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['studLastName'] = "Invalid Last Name entry. Reverting to original value";
    } else {
        $_SESSION['errors']['studLastName'] = "";
    }


    //check if student first name, middle name, or last name already exists in the database
 
    if(empty($_SESSION['errors']['studFirstName']) && empty($_SESSION['errors']['studMiddleName']) && empty($_SESSION['errors']['studLastName'])){
        
        $dbStatement = $db->prepare('UPDATE students SET studfirstname = ?, studmidname = ?, studlastname = ? WHERE studid = ?');
        $dbResult = $dbStatement->execute([
            $studFirstName,
            $studMiddleName,
            $studLastName,
            $studID
        ]);

        if($dbResult){
            $_SESSION['messages']['updateSuccess'] = "Student entry updated successfully";
            $_SESSION['messages']['updateError'] = "";
        } else {
            $_SESSION['messages']['updateError'] = "Failed to update student entry";
            $_SESSION['messages']['updateSuccess'] = "";
        }

        header("Location: $entryURL", true, 301);

    } else {
        header("Location: $entryURL", true, 301);
    }
}

?>