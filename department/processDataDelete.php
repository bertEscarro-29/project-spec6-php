<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];


if($_POST && isset($_POST['confirmDelete'])){
    $departmentID = $_POST['departmentID'];
    
  
        //check if the department entry has associated program entries, if it does, prevent deletion and return an error message

        $dbStatement = $db->prepare("SELECT * FROM programs WHERE progcolldeptid = :departmentID");
        $dbStatement->execute(['departmentID' => $departmentID]);
        $programs = $dbStatement->fetchAll(PDO::FETCH_ASSOC);

        if(count($programs) > 0){
            $_SESSION['messages']['updateError'] = "Cannot delete department entry because it has associated program entries";
            $_SESSION['messages']['updateSuccess'] = "";
            header("Location: $entryURL", true, 301);
            exit();
        }

            $dbStatement = $db->prepare('DELETE FROM departments WHERE deptid = ?');
            $dbResult = $dbStatement->execute([
                $departmentID
            ]);

            if($dbResult){
                $_SESSION['messages']['updateSuccess'] = "Department entry deleted successfully";
                $_SESSION['messages']['updateError'] = "";
            } else {
                $_SESSION['messages']['updateError'] = "Failed to delete department entry";
                $_SESSION['messages']['updateSuccess'] = "";
            }

        header("Location: $entryURL", true, 301);

       
    }

?>